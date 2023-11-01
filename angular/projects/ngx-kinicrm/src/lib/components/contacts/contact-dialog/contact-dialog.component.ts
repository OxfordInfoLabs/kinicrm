import { Component } from '@angular/core';
import {MatDialogRef} from '@angular/material/dialog';

@Component({
  selector: 'kcrm-contact-dialog',
  templateUrl: './contact-dialog.component.html',
  styleUrls: ['./contact-dialog.component.css']
})
export class ContactDialogComponent {

    constructor(public dialogRef: MatDialogRef<ContactDialogComponent>) {
    }

    public contactSaved(organisation: any) {
        this.dialogRef.close(organisation);
    }


}
