import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import * as _ from 'lodash';
import * as moment from 'moment';
import {KinicrmModuleConfig} from '../ngx-kinicrm.module';


@Injectable({
    providedIn: 'root'
})
export class ContactService {

    constructor(private http: HttpClient,
                private config: KinicrmModuleConfig) {
    }

    public getContacts(filterString = '', limit = 10, offset = 0) {
        const contacts = localStorage.getItem('contacts');
        return contacts ? JSON.parse(contacts) : [];
    }

    public getContact(id: number) {
        const contacts = this.getContacts();
        return _.find(contacts, {id});
    }

    public saveContact(contact: any) {
        contact.created = moment().toISOString();
        const contacts = this.getContacts();
        contacts.push(contact);
        localStorage.setItem('contacts', JSON.stringify(contacts));
    }

    public deleteContact(id: number) {
        const contacts = this.getContacts();
        _.remove(contacts, {id});
        localStorage.setItem('contacts', JSON.stringify(contacts));
    }
}
