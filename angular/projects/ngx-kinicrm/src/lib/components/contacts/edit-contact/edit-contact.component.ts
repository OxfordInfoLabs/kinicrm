import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {ContactService} from '../../../services/contact.service';

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

    constructor(private route: ActivatedRoute,
                private contactService: ContactService) {
    }

    ngOnInit() {
        this.route.params.subscribe((params: any) => {
            if (params.id) {
                this.contact = this.contactService.getContact(params.id) || {
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

    public handleAddressChange(event: any) {
        console.log(event);
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

    public addOrganisation() {

    }

}
