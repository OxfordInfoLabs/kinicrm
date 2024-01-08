import {ModuleWithProviders, NgModule} from '@angular/core';
import {ContactsComponent} from './components/contacts/contacts.component';
import {BrowserModule} from '@angular/platform-browser';
import {CommonModule, NgOptimizedImage} from '@angular/common';
import {MatIconModule} from '@angular/material/icon';
import { EditContactComponent } from './components/contacts/edit-contact/edit-contact.component';
import {RouterLink, RouterModule} from '@angular/router';
import {HttpClientModule} from '@angular/common/http';
import { GooglePlaceModule } from "ngx-google-places-autocomplete";
import { OrganisationsComponent } from './components/organisations/organisations.component';
import { EditOrganisationComponent } from './components/organisations/edit-organisation/edit-organisation.component';
import { AddressBookComponent } from './components/address-book/address-book.component';
import { DepartmentsComponent } from './components/departments/departments.component';
import { EditDepartmentComponent } from './components/departments/edit-department/edit-department.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {MatSelectModule} from '@angular/material/select';
import {MatButtonModule} from '@angular/material/button';
import {AddressComponent} from './components/address-book/address/address.component';
import {MatDialogModule} from '@angular/material/dialog';
import { AddressDialogComponent } from './components/address-book/address-dialog/address-dialog.component';
import { OrganisationDialogComponent } from './components/organisations/organisation-dialog/organisation-dialog.component';
import { ContactDialogComponent } from './components/contacts/contact-dialog/contact-dialog.component';
import {MatAutocompleteModule} from '@angular/material/autocomplete';
import {MatChipsModule} from '@angular/material/chips';
import {MatCheckboxModule} from '@angular/material/checkbox';
import {MatTabsModule} from '@angular/material/tabs';
import { TasksComponent } from './components/tasks/tasks.component';


@NgModule({
    declarations: [
        ContactsComponent,
        EditContactComponent,
        AddressComponent,
        OrganisationsComponent,
        EditOrganisationComponent,
        AddressBookComponent,
        DepartmentsComponent,
        EditDepartmentComponent,
        AddressDialogComponent,
        OrganisationDialogComponent,
        ContactDialogComponent,
        TasksComponent
    ],
    imports: [
        BrowserModule,
        CommonModule,
        MatIconModule,
        HttpClientModule,
        RouterModule,
        GooglePlaceModule,
        NgOptimizedImage,
        FormsModule,
        MatSelectModule,
        MatButtonModule,
        MatDialogModule,
        MatAutocompleteModule,
        MatChipsModule,
        MatCheckboxModule,
        ReactiveFormsModule,
        MatTabsModule
    ],
    exports: [
        ContactsComponent,
        EditContactComponent,
        AddressBookComponent,
        AddressComponent,
        OrganisationsComponent,
        EditOrganisationComponent
    ]
})
export class NgxKinicrmModule {
    static forRoot(conf?: KinicrmModuleConfig): ModuleWithProviders<NgxKinicrmModule> {
        return {
            ngModule: NgxKinicrmModule,
            providers: [
                {provide: KinicrmModuleConfig, useValue: conf || {}}
            ]
        };
    }
}

export class KinicrmModuleConfig {
    guestHttpURL?: string;
    accessHttpURL?: string;
    adminHttpURL?: string;
}
