import { Component } from '@angular/core';

@Component({
  selector: 'kcrm-edit-organisation',
  templateUrl: './edit-organisation.component.html',
  styleUrls: ['./edit-organisation.component.css']
})
export class EditOrganisationComponent {

    public organisation: any = {};

    constructor() {
    }

    public logoUpload(event: any) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onloadend = () => {
            this.organisation.logo = reader.result;
        };
        reader.readAsDataURL(file);
    }
}
