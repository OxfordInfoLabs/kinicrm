import {Component, OnInit, Output, EventEmitter, Input} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {ContactService} from '../../../services/contact.service';
import {AddressService} from '../../../services/address.service';
import {MatDialog} from '@angular/material/dialog';
import {AddressDialogComponent} from '../../address-book/address-dialog/address-dialog.component';
import {OrganisationService} from '../../../services/organisation.service';
import {OrganisationDialogComponent} from '../../organisations/organisation-dialog/organisation-dialog.component';
import {Location} from '@angular/common';

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

    constructor(private route: ActivatedRoute,
                private contactService: ContactService,
                private addressService: AddressService,
                private dialog: MatDialog,
                private organisationService: OrganisationService,
                private location: Location) {
    }

    async ngOnInit() {
        this.loadAddresses();
        this.loadOrganisations();

        this.route.params.subscribe(async (params: any) => {
            if (params.id) {
                this.contact = await this.contactService.getContact(params.id) || {
                    organisationDepartments: [{}]
                };
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
        this.contact =  await this.contactService.saveContact(this.contact);
        this.contactSaved.next(this.contact);
        if (this.back) {
            this.location.back();
        }
    }

    private async loadAddresses() {
        this.addresses = await this.addressService.searchForAddresses('', 1000, 0).toPromise();
    }

    private async loadOrganisations() {
        this.organisations = await this.organisationService.searchForOrganisations('', 1000, 0).toPromise();
    }

}
