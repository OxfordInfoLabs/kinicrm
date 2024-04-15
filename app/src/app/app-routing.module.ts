import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {LoginComponent} from './views/login/login.component';
import {AuthGuard} from './guards/auth.guard';
import {AppComponent} from './views/app.component';
import {HomeComponent} from './views/home/home.component';
import {ContactsComponent} from './views/contacts/contacts.component';
import {EditContactComponent} from './views/contacts/edit-contact/edit-contact.component';
import {OrganisationsComponent} from './views/organisations/organisations.component';
import {EditOrganisationComponent} from './views/organisations/edit-organisation/edit-organisation.component';
import {SettingsComponent} from './views/settings/settings.component';
import {AddressBookComponent} from './views/settings/address-book/address-book.component';
import {EditAddressComponent} from './views/settings/address-book/edit-address/edit-address.component';
import {TasksComponent} from './views/tasks/tasks.component';
import {TaskComponent} from './views/tasks/task/task.component';

const routes: Routes = [
    {
        path: '',
        redirectTo: '/home',
        pathMatch: 'full'
    },
    {
        path: 'home',
        component: AppComponent,
        children: [
            {
                path: '',
                component: HomeComponent,
                data: {
                    title: 'Home'
                }
            }
        ]
    },
    {
        path: 'crm',
        component: AppComponent,
        data: {
            title: 'CRM'
        },
        children: [
            {
                path: 'contacts',
                component: ContactsComponent,
                canActivate: [AuthGuard],
                data: {
                    title: 'Contacts'
                }
            },
            {
                path: 'contacts/:id',
                component: EditContactComponent,
                canActivate: [AuthGuard],
                data: {
                    title: 'Edit Contact'
                }
            },
            {
                path: 'organisations',
                component: OrganisationsComponent,
                canActivate: [AuthGuard],
                data: {
                    title: 'Organisations'
                }
            },
            {
                path: 'organisations/:id',
                component: EditOrganisationComponent,
                canActivate: [AuthGuard],
                data: {
                    title: 'Edit Organisation'
                }
            },
            {
                path: 'settings',
                component: SettingsComponent,
                canActivate: [AuthGuard],
                data: {
                    title: 'Settings'
                }
            },
            {
                path: 'settings/address-book',
                component: AddressBookComponent,
                canActivate: [AuthGuard],
                data: {
                    title: 'Address Book'
                }
            },
            {
                path: 'settings/address-book/:id',
                component: EditAddressComponent,
                canActivate: [AuthGuard],
                data: {
                    title: 'Edit Address'
                }
            },
            {
                path: 'tasks',
                component: TasksComponent,
                canActivate: [AuthGuard],
                data: {
                    title: 'Tasks'
                }
            },
            {
                path: 'tasks/:id',
                component: TaskComponent,
                canActivate: [AuthGuard],
                data: {
                    title: 'Edit Task'
                }
            }
        ]
    },
    {
        path: 'login',
        component: LoginComponent
    },
    {
        path: '**',
        redirectTo: 'home',
        pathMatch: 'full'
    }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
