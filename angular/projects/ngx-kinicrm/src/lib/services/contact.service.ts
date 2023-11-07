import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
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

    public deleteContact(contactId: number) {
        return this.http.delete(this.config.adminHttpURL + '/crm/contact', {
            params: {contactId}
        }).toPromise();
    }

    public uploadAttachments(contactId: number, fileUploads: any) {
        const HttpUploadOptions = {
            headers: new HttpHeaders({ 'Content-Type': 'file' })
        };
        return this.http.post(this.config.adminHttpURL + '/crm/contact/attachments/' + contactId,
            fileUploads, HttpUploadOptions)
            .toPromise();
    }

    public removeAttachment(contactId: number, attachmentId: number) {
        return this.http.delete(this.config.adminHttpURL + `/crm/contact/attachments/${contactId}/${attachmentId}`)
            .toPromise();
    }

    public streamAttachment(id: number) {
        window.open(this.config.accessHttpURL + '/attachment/' + id);
    }
}
