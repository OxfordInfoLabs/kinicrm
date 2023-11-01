import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {ContactService} from '../../../services/contact.service';
import {AddressService} from '../../../services/address.service';
import {MatDialog} from '@angular/material/dialog';
import {AddressDialogComponent} from '../../address-book/address-dialog/address-dialog.component';

@Component({
  selector: 'kcrm-edit-contact',
  templateUrl: './edit-contact.component.html',
  styleUrls: ['./edit-contact.component.css']
})
export class EditContactComponent implements OnInit {

    public contact: any = {
        organisationDepartments: [
            {
                organisation: null,
                department: null
            }
        ]
    };
    public addresses: any = [];

    constructor(private route: ActivatedRoute,
                private contactService: ContactService,
                private addressService: AddressService,
                private dialog: MatDialog) {
    }

    async ngOnInit() {
        this.loadAddresses();
        this.route.params.subscribe(async (params: any) => {
            if (params.id) {
                this.contact = await this.contactService.getContact(params.id) || {
                    organisationDepartments: [
                        {
                            organisation: null,
                            department: null
                        }
                    ]
                };
            }
            console.log(this.contact);
        });
    }

    public delete() {

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

    public addOrganisation() {

    }

    public addressDisplay(v1: any, v2: any) {
        return v1 && v2 && (v1.id === v2.id);
    }


    private async loadAddresses() {
        this.addresses = await this.addressService.searchForAddresses('', 1000, 0).toPromise();
    }

}
