import {Component, OnInit, Output, EventEmitter, Input, ViewChild, ElementRef} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
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
import {AuthenticationService, CommunicationService} from 'ng-kiniauth';
import {BehaviorSubject, merge} from 'rxjs';
import {debounceTime, map, switchMap} from 'rxjs/operators';
import { MatOptionSelectionChange } from '@angular/material/core';
import {MetadataService} from '../../../services/metadata.service';
import {COMMA, ENTER} from '@angular/cdk/keycodes';
import {MatChipInputEvent} from '@angular/material/chips';
import {MatAutocompleteSelectedEvent} from '@angular/material/autocomplete';
import * as _ from 'lodash';
import moment from 'moment';
import {DomSanitizer} from '@angular/platform-browser';
import {MatTabChangeEvent} from '@angular/material/tabs';
import {MatSnackBar} from '@angular/material/snack-bar';

@Component({
    selector: 'kcrm-edit-contact',
    templateUrl: './edit-contact.component.html',
    styleUrls: ['./edit-contact.component.css']
})
export class EditContactComponent implements OnInit {

    @Output() contactSaved = new EventEmitter();

    @Input() back = true;

    @ViewChild('tagInput') tagInput: ElementRef<HTMLInputElement> | undefined;
    @ViewChild('categoryInput') categoryInput: ElementRef<HTMLInputElement> | undefined;

    public moment = moment;
    public _ = _;
    public contact: any = {
        organisationDepartments: [{}]
    };
    public addresses: any = [];
    public tags: any = [];
    public categories: any = [];
    public organisations: any = [];
    public edit = false;
    public loading = true;
    public comments: any = [];
    public loggedInUser: any = {};
    public loggedInGravatar: string = '';
    public organisationSearch = new BehaviorSubject('');
    public addressSearch = new BehaviorSubject('');
    public tagSearch = new BehaviorSubject('');
    public categorySearch = new BehaviorSubject('');
    public separatorKeysCodes: number[] = [ENTER, COMMA];
    public emailExists = false;
    public mailingLists: any = [];
    public contactEmails: any = [];
    public selectedIndex = 0;

    private currentURL = '';

    constructor(private route: ActivatedRoute,
                private contactService: ContactService,
                private addressService: AddressService,
                private dialog: MatDialog,
                private organisationService: OrganisationService,
                private location: Location,
                private http: HttpClient,
                private commentService: CommentService,
                private gravatarService: GravatarService,
                private authService: AuthenticationService,
                private metadataService: MetadataService,
                private communicationService: CommunicationService,
                private sanitise: DomSanitizer,
                private router: Router,
                private snackBar: MatSnackBar) {
    }

    async ngOnInit() {
        this.currentURL = window.location.pathname.split('#')[0];

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

        merge(this.tagSearch)
            .pipe(
                debounceTime(300),
                // distinctUntilChanged(),
                switchMap(() =>
                    this.loadTags()
                )
            ).subscribe((tags: any) => {
            this.tags = tags;
        });

        merge(this.categorySearch)
            .pipe(
                debounceTime(300),
                // distinctUntilChanged(),
                switchMap(() =>
                    this.loadCategories()
                )
            ).subscribe((categories: any) => {
            this.categories = categories;
        });

        this.route.params.subscribe(async (params: any) => {
            await this.loadContact(params.id);

            if (!this.contact.organisationDepartments.length) {
                this.contact.organisationDepartments = [{}];
            }

            if (this.contact.emailAddress) {
                this.contactEmails = await this.communicationService.filterStoredEmails(this.contact.emailAddress).toPromise();
            }

            this.route.fragment.subscribe((fragment: any) => {
                if (fragment !== null) {
                    this.selectedIndex = fragment;
                }
            });
        });


        const params: any = this.route.snapshot.params;
        if (!params.id) {
            this.loading = false;
        }

        this.loggedInUser = this.authService.authUser.getValue();
        this.loggedInGravatar = await this.gravatarService.getGravatarURL(this.loggedInUser.emailAddress);

        this.mailingLists = await this.contactService.getMailingLists();
    }

    public copied() {
        this.snackBar.open('Copied to Clipboard', '', {
            duration: 2000,
            verticalPosition: 'bottom'
        });
    }

    public tabChange(event: MatTabChangeEvent) {
        this.router.navigate([this.currentURL], {fragment: (event.index).toString()});
    }

    public async previewEmail(email: any) {
        this.contactEmails.map((contactEmail: any) => {
            contactEmail.preview = false;
            return contactEmail;
        });

        email.preview = true;
        if (!email.previewContent) {
            const previewContent: any = await this.communicationService.getStoredEmailContent(email.id);
            email.previewContent = this.sanitise.bypassSecurityTrustHtml(previewContent || '');
        }
    }

    public async updateMailingList(event: any, mailingList: any, subscriptions: any) {

        const checked = event.target.checked;
        if (checked) {
            await this.contactService.subscribeToMailingList(mailingList.key, {
                name: this.contact.name,
                emailAddress: this.contact.emailAddress
            });
        } else {
            const subscribedMailingList: any = _.find(subscriptions, {mailingListId: mailingList.id});
            await this.contactService.unsubscribeToMailingList(subscribedMailingList.unsubscribeKey, subscribedMailingList.emailHash);
        }

        this.loadContact(this.contact.id);
    }

    public updateOrganisation(event: MatOptionSelectionChange, index: number) {
        this.contact.organisationDepartments[index].organisationSummary = event.source.value;
    }

    public updateAddress(event: MatOptionSelectionChange) {
        this.contact.address = event.source.value;
    }

    public removeTag(tag: any) {
        _.remove(this.contact.tags, {name: tag.name});
    }

    public selectedTag(event: MatAutocompleteSelectedEvent) {
        if (!this.contact.tags || !Array.isArray(this.contact.tags)) {
            this.contact.tags = [];
        }

        this.contact.tags.push(event.option.value);
        if (this.tagInput) {
            this.tagInput.nativeElement.value = '';
        }
    }

    public addTag(event: MatChipInputEvent) {
        if (!this.contact.tags || !Array.isArray(this.contact.tags)) {
            this.contact.tags = [];
        }

        const value = (event.value || '').trim();

        const existing = _.find(this.tags, (tag: any) => {
            return tag.name.toLowerCase() === value.toLowerCase();
        });

        this.contact.tags.push(existing || {name: value});

        event.chipInput!.clear();
    }

    public removeCategory(category: any) {
        _.remove(this.contact.categories, {name: category.name});
    }

    public selectedCategory(event: MatAutocompleteSelectedEvent) {
        if (!this.contact.categories || !Array.isArray(this.contact.categories)) {
            this.contact.categories = [];
        }

        this.contact.categories.push(event.option.value);
        if (this.categoryInput) {
            this.categoryInput.nativeElement.value = '';
        }
    }

    public addCategory(event: MatChipInputEvent) {
        if (!this.contact.categories || !Array.isArray(this.contact.categories)) {
            this.contact.categories = [];
        }

        const value = (event.value || '').trim();

        const existing = _.find(this.categories, (category: any) => {
            return category.name.toLowerCase() === value.toLowerCase();
        });

        this.contact.categories.push(existing || {name: value});
        event.chipInput!.clear();
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

        this.emailExists = await this.contactService.doesEmailAddressExist(this.contact.emailAddress);
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
            {search: this.organisationSearch.getValue() || ''}, 1000, 0)
            .pipe(map((organisations: any) => {
                    return organisations;
                })
            );
    }

    private loadTags() {
        return this.metadataService.searchForTags(
            this.tagSearch.getValue() || '', 1000, 0)
            .pipe(map((tags: any) => {
                    return tags;
                })
            );
    }

    private loadCategories() {
        return this.metadataService.searchForCategories(
            this.categorySearch.getValue() || '', 1000, 0)
            .pipe(map((categories: any) => {
                    return categories;
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

        this.contact.subscribedMailingListIds = _.map(this.contact.subscribedMailingLists, 'mailingListId');

        this.loading = false;
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
