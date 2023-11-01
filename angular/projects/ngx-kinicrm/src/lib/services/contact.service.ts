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

    public searchForContacts(searchString = '', limit = 10, offset = 0) {
        const url = this.config.adminHttpURL + '/crm/contact';
        return this.http.get(url, {
            params: {
                searchString, limit: limit.toString(), offset: offset.toString()
            }
        })
    }

    public getContact(id: number) {
        return this.http.get(this.config.adminHttpURL + '/crm/contact/' + id).toPromise();
    }

    public saveContact(address: any) {
        return this.http.post(this.config.adminHttpURL + '/crm/contact', address).toPromise();
    }

    public deleteContact(addressId: number) {
        return this.http.delete(this.config.adminHttpURL + '/crm/contact', {
            params: {addressId}
        }).toPromise();
    }
}
