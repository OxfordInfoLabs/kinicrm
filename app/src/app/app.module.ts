import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {environment} from '../environments/environment';
import {AppRoutingModule} from './app-routing.module';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {SessionInterceptor} from './session.interceptor';
import {HTTP_INTERCEPTORS, HttpClientModule} from '@angular/common/http';
import {NgKinintelModule} from 'ng-kinintel';
import {NgKiniAuthModule} from 'ng-kiniauth';
import {MatLegacyProgressBarModule as MatProgressBarModule} from '@angular/material/legacy-progress-bar';
import {MatToolbarModule} from '@angular/material/toolbar';
import {MatLegacyButtonModule as MatButtonModule} from '@angular/material/legacy-button';
import {MatIconModule} from '@angular/material/icon';
import {MatLegacyMenuModule as MatMenuModule} from '@angular/material/legacy-menu';
import {MatSidenavModule} from '@angular/material/sidenav';
import {MatChipsModule} from '@angular/material/chips';
import {MatLegacyTooltipModule as MatTooltipModule} from '@angular/material/legacy-tooltip';
import { LoginComponent } from './views/login/login.component';
import {MatBadgeModule} from '@angular/material/badge';
import { RouterComponent } from './router/router.component';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {MainComponent} from './main.component';
import {MatDatepickerModule} from '@angular/material/datepicker';
import {MatNativeDateModule} from '@angular/material/core';
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatSelectModule} from '@angular/material/select';
import {ScrollingModule} from '@angular/cdk/scrolling';
import {MatProgressSpinnerModule} from '@angular/material/progress-spinner';
import {NgxResizableModule} from '@3dgenomes/ngx-resizable';
import {QuillModule} from 'ngx-quill';
import {MatCheckboxModule} from '@angular/material/checkbox';
import {DragDropModule} from '@angular/cdk/drag-drop';
import {EditorModule} from '@tinymce/tinymce-angular';
import {MatDialogModule} from '@angular/material/dialog';
import {MatListModule} from '@angular/material/list';
import {MatRadioModule} from '@angular/material/radio';
import {MatStepperModule} from '@angular/material/stepper';
import {CdkStepperModule} from '@angular/cdk/stepper';
import {MatAutocompleteModule} from '@angular/material/autocomplete';
import {MatTabsModule} from '@angular/material/tabs';
import {MatSnackBarModule} from '@angular/material/snack-bar';
import {AppComponent} from './views/app.component';
import { HomeComponent } from './views/home/home.component';
import {NgxKinicrmModule} from 'ngx-kinicrm';
import { ContactsComponent } from './views/contacts/contacts.component';
import { EditContactComponent } from './views/contacts/edit-contact/edit-contact.component';
import { OrganisationsComponent } from './views/organisations/organisations.component';
import { EditOrganisationComponent } from './views/organisations/edit-organisation/edit-organisation.component';
import { SettingsComponent } from './views/settings/settings.component';
import { AddressBookComponent } from './views/settings/address-book/address-book.component';
import { EditAddressComponent } from './views/settings/address-book/edit-address/edit-address.component';
import { TasksComponent } from './views/tasks/tasks.component';
import { TaskComponent } from './views/tasks/task/task.component';

@NgModule({
    declarations: [
        MainComponent,
        LoginComponent,
        RouterComponent,
        AppComponent,
        HomeComponent,
        ContactsComponent,
        EditContactComponent,
        OrganisationsComponent,
        EditOrganisationComponent,
        SettingsComponent,
        AddressBookComponent,
        EditAddressComponent,
        TasksComponent,
        TaskComponent
    ],
    imports: [
        BrowserModule,
        HttpClientModule,
        CommonModule,
        FormsModule,
        ReactiveFormsModule,
        AppRoutingModule,
        BrowserAnimationsModule,
        NgKinintelModule.forRoot({
            backendURL: environment.accountURL
        }),
        NgKiniAuthModule.forRoot({
            guestHttpURL: `${environment.backendURL}/guest`,
            accessHttpURL: `${environment.backendURL}/admin`
        }),
        NgxKinicrmModule.forRoot({
            accessHttpURL: environment.backendURL,
            guestHttpURL: environment.backendURL + '/guest',
            adminHttpURL: environment.backendURL + '/admin'
        }),
        MatProgressBarModule,
        MatToolbarModule,
        MatButtonModule,
        MatIconModule,
        MatMenuModule,
        MatSidenavModule,
        MatTooltipModule,
        MatBadgeModule,
        MatDatepickerModule,
        MatNativeDateModule,
        MatFormFieldModule,
        MatSelectModule,
        ScrollingModule,
        MatProgressSpinnerModule,
        NgxResizableModule,
        QuillModule.forRoot(),
        MatCheckboxModule,
        DragDropModule,
        MatIconModule,
        EditorModule,
        MatDialogModule,
        MatListModule,
        MatRadioModule,
        MatStepperModule,
        CdkStepperModule,
        MatAutocompleteModule,
        MatChipsModule,
        MatTabsModule,
        MatSnackBarModule
    ],
    providers: [
        {
            provide: HTTP_INTERCEPTORS,
            useClass: SessionInterceptor,
            multi: true
        }
    ],
    bootstrap: [RouterComponent]
})
export class AppModule {
}
