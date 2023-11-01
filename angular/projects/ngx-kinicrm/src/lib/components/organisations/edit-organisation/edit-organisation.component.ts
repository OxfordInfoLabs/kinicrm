import {Component, OnInit, Output, Input, EventEmitter} from '@angular/core';
import {AddressService} from '../../../services/address.service';
import {AddressDialogComponent} from '../../address-book/address-dialog/address-dialog.component';
import {MatDialog} from '@angular/material/dialog';
import {OrganisationService} from '../../../services/organisation.service';
import {ActivatedRoute} from '@angular/router';
import {Location} from '@angular/common';

@Component({
  selector: 'kcrm-edit-organisation',
  templateUrl: './edit-organisation.component.html',
  styleUrls: ['./edit-organisation.component.css']
})
export class EditOrganisationComponent implements OnInit {

    @Output() organisationSaved = new EventEmitter();

    @Input() back = true;

    public organisation: any = {
        departments: [{}]
    };
    public addresses: any = [];

    constructor(private addressService: AddressService,
                private dialog: MatDialog,
                private organisationService: OrganisationService,
                private route: ActivatedRoute,
                private location: Location) {
    }

    async ngOnInit() {
        this.loadAddresses();
        this.route.params.subscribe(async (params: any) => {
            if (params.id) {
                this.organisation = await this.organisationService.getOrganisation(params.id) || {
                    departments: [{}]
                };
            }
        })
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

    public addressDisplay(v1: any, v2: any) {
        return v1 && v2 && (v1.id === v2.id);
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

    private async loadAddresses() {
        this.addresses = await this.addressService.searchForAddresses('', 1000, 0).toPromise();
    }
}
