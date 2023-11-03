import { Component } from '@angular/core';
import {MatDialogRef} from '@angular/material/dialog';

@Component({
  selector: 'kcrm-organisation-dialog',
  templateUrl: './organisation-dialog.component.html',
  styleUrls: ['./organisation-dialog.component.css']
})
export class OrganisationDialogComponent {

    constructor(public dialogRef: MatDialogRef<OrganisationDialogComponent>) {
    }

    public organisationSaved(organisation: any) {
        this.dialogRef.close(organisation);
    }

}
