import {Component, OnInit, Output, EventEmitter, Input} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {ContactService} from '../../../services/contact.service';
import {AddressService} from '../../../services/address.service';
import {MatDialog} from '@angular/material/dialog';
import {AddressDialogComponent} from '../../address-book/address-dialog/address-dialog.component';
import {OrganisationService} from '../../../services/organisation.service';
import {OrganisationDialogComponent} from '../../organisations/organisation-dialog/organisation-dialog.component';
import {Location} from '@angular/common';
import {HttpClient} from '@angular/common/http';
import {CommentService} from '../../../services/comment.service';
import {GravatarService} from '../../../services/gravatar.service';
import {AuthenticationService} from 'ng-kiniauth';
import {BehaviorSubject, merge} from 'rxjs';
import {debounceTime, map, switchMap} from 'rxjs/operators';
import { MatOptionSelectionChange } from '@angular/material/core';

@Component({
    selector: 'kcrm-edit-contact',
    templateUrl: './edit-contact.component.html',
    styleUrls: ['./edit-contact.component.css']
})
export class EditContactComponent implements OnInit {

    @Output() contactSaved = new EventEmitter();

    @Input() back = true;

    public contact: any = {
        organisationDepartments: [{}]
    };
    public addresses: any = [];
    public organisations: any = [];
    public edit = false;
    public loading = true;
    public comments: any = [];
    public loggedInUser: any = {};
    public loggedInGravatar: string = '';
    public organisationSearch = new BehaviorSubject('');
    public addressSearch = new BehaviorSubject('');

    constructor(private route: ActivatedRoute,
                private contactService: ContactService,
                private addressService: AddressService,
                private dialog: MatDialog,
                private organisationService: OrganisationService,
                private location: Location,
                private http: HttpClient,
                private commentService: CommentService,
                private gravatarService: GravatarService,
                private authService: AuthenticationService) {
    }

    async ngOnInit() {
        merge(this.organisationSearch)
            .pipe(
                debounceTime(300),
                // distinctUntilChanged(),
                switchMap(() =>
                    this.loadOrganisations()
                )
            ).subscribe((organisations: any) => {
            this.organisations = organisations;
        });

        merge(this.addressSearch)
            .pipe(
                debounceTime(300),
                // distinctUntilChanged(),
                switchMap(() =>
                    this.loadAddresses()
                )
            ).subscribe((addresses: any) => {
            this.addresses = addresses;
        });

        this.route.params.subscribe(async (params: any) => {
            await this.loadContact(params.id);

            if (!this.contact.organisationDepartments.length) {
                this.contact.organisationDepartments = [{}];
            }

            if (this.contact.id) {
                this.loadComments();
            }
        });

        const params: any = this.route.snapshot.params;
        if (!params.id) {
            this.loading = false;
        }

        this.loggedInUser = this.authService.authUser.getValue();
        this.loggedInGravatar = await this.gravatarService.getGravatarURL(this.loggedInUser.emailAddress);
    }

    public updateOrganisation(event: MatOptionSelectionChange, index: number) {
        this.contact.organisationDepartments[index].organisationSummary = event.source.value;
    }

    public updateAddress(event: MatOptionSelectionChange) {
        this.contact.address = event.source.value;
    }

    public async emailUpdated() {
        if (this.contact.id) {
            const message = 'Would you like to update this users photo to their Gravatar image (if available)?';
            if (window.confirm(message)) {
                this.updateContactGravatar();
            }
        } else {
            this.updateContactGravatar();
        }
    }

    public async updateContactGravatar() {
        const textAsBuffer = new TextEncoder().encode((this.contact.emailAddress).toLowerCase());
        const hashBuffer = await window.crypto.subtle.digest('SHA-256', textAsBuffer);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        const hash = hashArray
            .map((item) => item.toString(16).padStart(2, '0'))
            .join('');

        this.toDataURL('https://gravatar.com/avatar/' + hash + '?d=404&s=512', (dataURL: any) => {
            if (dataURL) {
                this.contact.photo = dataURL;
            }
        });
    }

    public async deleteContact() {
        const message = 'Are you sure you would like to delete this contact?';
        if (window.confirm(message)) {
            await this.contactService.deleteContact(this.contact.id);
            this.location.back();
        }
    }

    public addOrganisation() {
        const dialogRef = this.dialog.open(OrganisationDialogComponent, {
            height: '745px',
            width: '900px',
        });

        dialogRef.afterClosed().subscribe(async newOrganisation => {
            await this.loadOrganisations();
        });
    }

    public removeOrganisation(index: number) {
        const message = 'Are you sure you would like to remove this Organisation from the Contact?';
        if (window.confirm(message)) {
            this.contact.organisationDepartments.splice(index, 1);
        }
    }

    public photoUpload(event: any) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onloadend = () => {
            this.contact.photo = reader.result;
        };
        reader.readAsDataURL(file);
    }

    public async addComment(target: any, event?: any) {
        if (event) {
            const keyCode = event.which || event.keyCode;
            if (keyCode === 13 && !event.shiftKey) {
                event.preventDefault();

                this.sendComment(target);
            }
        } else {
            this.sendComment(target);
        }
    }

    public async attachmentsUpload(event: any) {
        const files: any[] = Array.from(event.target.files);

        const formData = new FormData();

        for (const file of files) {
            formData.append(file.name, file);
        }

        await this.contactService.uploadAttachments(this.contact.id, formData);
        this.loadContact(this.contact.id);
    }

    public async removeAttachment(attachmentId: number) {
        await this.contactService.removeAttachment(this.contact.id, attachmentId);
        this.loadContact(this.contact.id);
    }

    public streamAttachment(id: number) {
        this.contactService.streamAttachment(id);
    }

    public addAddress() {
        const dialogRef = this.dialog.open(AddressDialogComponent, {
            height: '745px',
            width: '900px',
        });

        dialogRef.afterClosed().subscribe(async newAddress => {
            await this.loadAddresses();
            this.contact.address = newAddress;
        });
    }

    public compareWith(v1: any, v2: any) {
        return v1 && v2 && (v1.id === v2.id);
    }

    public async saveContact() {
        this.contact = await this.contactService.saveContact(this.contact);
        this.contactSaved.next(this.contact);
        if (this.back) {
            this.location.back();
        }
    }

    private async sendComment(target: any) {
        if (target.value) {
            await this.commentService.createComment('Contact', this.contact.id, target.value);
        }

        target.value = '';
        this.loadComments();
    }

    private loadAddresses() {
        return this.addressService.searchForAddresses(
            this.addressSearch.getValue() || '', 1000, 0)
            .pipe(map((addresses: any) => {
                    return addresses;
                })
            );
    }

    private loadOrganisations() {
        return this.organisationService.searchForOrganisations(
            this.organisationSearch.getValue() || '', 1000, 0)
            .pipe(map((organisations: any) => {
                    return organisations;
                })
            );
    }

    private async loadContact(id: number) {
        try {
            this.contact = await this.contactService.getContact(id) || {
                organisationDepartments: [{}]
            };
        } catch (e) {
        }

        this.loading = false;
    }

    private async loadComments() {
        this.comments = await this.commentService.searchForComments('Contact', this.contact.id).toPromise();
    }

    private toDataURL(url: string, callback: any) {
        const xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function(){
            if ( xhr.readyState == 4 ) {
                if ( xhr.status == 200 ) {
                    const reader = new FileReader();
                    reader.onloadend = function() {
                        callback(reader.result);
                    }
                    reader.readAsDataURL(xhr.response);
                } else {
                    callback(null);
                }
            }
        };
        xhr.onerror = function () {
            callback(null);
        };

        xhr.open('GET', url);
        xhr.responseType = 'blob';
        xhr.send();
    }

}
