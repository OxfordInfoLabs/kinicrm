import { Component } from '@angular/core';
import {MatDialogRef} from '@angular/material/dialog';

@Component({
  selector: 'kcrm-address-dialog',
  templateUrl: './address-dialog.component.html',
  styleUrls: ['./address-dialog.component.css']
})
export class AddressDialogComponent {

    constructor(public dialogRef: MatDialogRef<AddressDialogComponent>) {
    }

    public addressSaved(address: any) {
        this.dialogRef.close(address);
    }
}
