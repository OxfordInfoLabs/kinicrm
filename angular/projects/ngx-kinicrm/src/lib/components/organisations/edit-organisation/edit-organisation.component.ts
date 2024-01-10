import {Component, OnInit, Output, Input, EventEmitter, ViewChild, ElementRef} from '@angular/core';
import {AddressService} from '../../../services/address.service';
import {AddressDialogComponent} from '../../address-book/address-dialog/address-dialog.component';
import {MatDialog} from '@angular/material/dialog';
import {OrganisationService} from '../../../services/organisation.service';
import {ActivatedRoute, Router} from '@angular/router';
import {Location} from '@angular/common';
import {ContactService} from '../../../services/contact.service';
import {ContactDialogComponent} from '../../contacts/contact-dialog/contact-dialog.component';
import {CommentService} from '../../../services/comment.service';
import {GravatarService} from '../../../services/gravatar.service';
import {AuthenticationService} from 'ng-kiniauth';
import {BehaviorSubject, merge} from 'rxjs';
import {debounceTime, map, switchMap} from 'rxjs/operators';
import {MatOptionSelectionChange} from '@angular/material/core';
import {COMMA, ENTER} from '@angular/cdk/keycodes';
import * as _ from 'lodash';
import {MatAutocompleteSelectedEvent} from '@angular/material/autocomplete';
import {MatChipInputEvent} from '@angular/material/chips';
import {MetadataService} from '../../../services/metadata.service';
import {MatTabChangeEvent} from '@angular/material/tabs';

@Component({
    selector: 'kcrm-edit-organisation',
    templateUrl: './edit-organisation.component.html',
    styleUrls: ['./edit-organisation.component.css']
})
export class EditOrganisationComponent implements OnInit {

    @Output() organisationSaved = new EventEmitter();

    @Input() back = true;

    @ViewChild('tagInput') tagInput: ElementRef<HTMLInputElement> | undefined;
    @ViewChild('categoryInput') categoryInput: ElementRef<HTMLInputElement> | undefined;

    public organisation: any = {
        departments: []
    };
    public addresses: any = [];
    public contacts: any = [];
    public tags: any = [];
    public categories: any = [];
    public edit = false;
    public loading = true;
    public comments: any = [];
    public loggedInUser: any = {};
    public loggedInGravatar: string = '';
    public addressSearch = new BehaviorSubject('');
    public contactSearch = new BehaviorSubject('');
    public organisationContacts: any = [];
    public tagSearch = new BehaviorSubject('');
    public categorySearch = new BehaviorSubject('');
    public separatorKeysCodes: number[] = [ENTER, COMMA];
    public selectedIndex = 0;

    private currentURL = '';

    constructor(private addressService: AddressService,
                private dialog: MatDialog,
                private organisationService: OrganisationService,
                private route: ActivatedRoute,
                private location: Location,
                private contactService: ContactService,
                private commentService: CommentService,
                private gravatarService: GravatarService,
                private authService: AuthenticationService,
                private metadataService: MetadataService,
                private router: Router) {
    }

    async ngOnInit() {
        this.route.url.subscribe((url: any) => {
            this.currentURL = '/' + _.map(url, 'path').join('/');
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

        merge(this.contactSearch)
            .pipe(
                debounceTime(300),
                // distinctUntilChanged(),
                switchMap(() =>
                    this.loadContacts()
                )
            ).subscribe((contacts: any) => {
            this.contacts = contacts;
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
            await this.loadOrganisation(params.id);

            this.edit = !this.organisation.id;

            if (this.organisation.id) {
                this.loadComments();

                this.organisationContacts = await this.contactService.searchForContacts({
                    organisations: [this.organisation.name]
                }, 1000).toPromise();
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
    }

    public tabChange(event: MatTabChangeEvent) {
        this.router.navigate([this.currentURL], {fragment: (event.index).toString()});
    }

    public removeTag(tag: any) {
        _.remove(this.organisation.tags, {name: tag.name});
    }

    public selectedTag(event: MatAutocompleteSelectedEvent) {
        if (!this.organisation.tags || !Array.isArray(this.organisation.tags)) {
            this.organisation.tags = [];
        }

        this.organisation.tags.push(event.option.value);
        if (this.tagInput) {
            this.tagInput.nativeElement.value = '';
        }
    }

    public addTag(event: MatChipInputEvent) {
        if (!this.organisation.tags || !Array.isArray(this.organisation.tags)) {
            this.organisation.tags = [];
        }

        const value = (event.value || '').trim();

        const existing = _.find(this.tags, (tag: any) => {
            return tag.name.toLowerCase() === value.toLowerCase();
        });

        this.organisation.tags.push(existing || {name: value});

        event.chipInput!.clear();
    }

    public removeCategory(category: any) {
        _.remove(this.organisation.categories, {name: category.name});
    }

    public selectedCategory(event: MatAutocompleteSelectedEvent) {
        if (!this.organisation.categories || !Array.isArray(this.organisation.categories)) {
            this.organisation.categories = [];
        }

        this.organisation.categories.push(event.option.value);
        if (this.categoryInput) {
            this.categoryInput.nativeElement.value = '';
        }
    }

    public addCategory(event: MatChipInputEvent) {
        if (!this.organisation.categories || !Array.isArray(this.organisation.categories)) {
            this.organisation.categories = [];
        }

        const value = (event.value || '').trim();

        const existing = _.find(this.categories, (category: any) => {
            return category.name.toLowerCase() === value.toLowerCase();
        });

        this.organisation.categories.push(existing || {name: value});
        event.chipInput!.clear();
    }

    public updateAddress(event: MatOptionSelectionChange) {
        this.organisation.address = event.source.value;
    }

    public updateContact(event: MatOptionSelectionChange) {
        this.organisation.primaryContact = event.source.value;
    }

    public removeDepartment(index: number) {
        this.organisation.departments.splice(index, 1);
    }

    public logoUpload(event: any) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onloadend = () => {
            this.organisation.logo = reader.result;
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

        await this.organisationService.uploadAttachments(this.organisation.id, formData);
        this.loadOrganisation(this.organisation.id);
    }

    public streamAttachment(id: number) {
        this.organisationService.streamAttachment(id);
    }

    public async removeAttachment(attachmentId: number) {
        await this.organisationService.removeAttachment(this.organisation.id, attachmentId);
        this.loadOrganisation(this.organisation.id);
    }

    public addAddress() {
        const dialogRef = this.dialog.open(AddressDialogComponent, {
            height: '745px',
            width: '900px',
        });

        dialogRef.afterClosed().subscribe(async (newAddress: any) => {
            await this.loadAddresses();
            this.organisation.address = newAddress;
        });
    }

    public compareWith(v1: any, v2: any) {
        return v1 && v2 && (v1.id === v2.id);
    }

    public addContact() {
        const dialogRef = this.dialog.open(ContactDialogComponent, {
            height: '745px',
            width: '900px',
        });

        dialogRef.afterClosed().subscribe(async (newContact: any) => {
            await this.loadContacts();
            this.organisation.primaryContact = newContact;
        });
    }

    public deleteOrganisation() {
        const message = 'Are you sure you would like to remove this organisation?';
        if (window.confirm(message)) {
            this.organisationService.deleteOrganisation(this.organisation.id);
        }
    }

    public async saveOrganisation() {
        this.organisation = await this.organisationService.saveOrganisation(this.organisation);
        this.organisationSaved.next(this.organisation);
        if (this.back) {
            this.location.back();
        }
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

    private async sendComment(target: any) {
        if (target.value) {
            await this.commentService.createComment('Organisation', this.organisation.id, target.value);
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

    private loadContacts() {
        return this.contactService.searchForContacts(
            {search: this.contactSearch.getValue() || ''}, 1000, 0)
            .pipe(map((contacts: any) => {
                    return contacts;
                })
            );
    }

    private async loadComments() {
        this.comments = await this.commentService.searchForComments('Organisation', this.organisation.id).toPromise();
    }

    private async loadOrganisation(id: number) {
        try {
            this.organisation = await this.organisationService.getOrganisation(id) || {
                departments: []
            };
        } catch (e) {
        }

        this.loading = false;
    }
}
