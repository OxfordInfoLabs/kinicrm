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

    public searchForContacts(filters: any = {}, limit = 10, offset = 0) {
        const url = this.config.adminHttpURL + '/crm/contact/search?limit=' + limit + '&offset=' + offset;
        return this.http.post(url, filters)
    }

    public getContactFilterValues(memberName: string, filters: any = []) {
        return this.http.post(this.config.adminHttpURL + '/crm/contact/filterValues/' + memberName, filters);
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
            headers: new HttpHeaders({'Content-Type': 'file'})
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

    public async doesEmailAddressExist(emailAddress: string) {
        const url = this.config.adminHttpURL + '/crm/contact/search?limit=1&offset=0';
        const contact: any = await this.http.post(url, {emailAddress}).toPromise();
        return !!contact.length;
    }

    public getMailingLists() {
        return this.http.get(this.config.adminHttpURL + '/mailingList?limit=1000&offset=0&accountId=0')
            .toPromise();
    }

    public subscribeToMailingList(mailingListKey: string, mailingListSubscriber: any) {
        return this.http.post(this.config.guestHttpURL + '/mailingList/subscribe/' + mailingListKey, mailingListSubscriber)
            .toPromise();
    }

    public unsubscribeToMailingList(unsubscribeKey: string, emailHash: string) {
        return this.http.get(this.config.guestHttpURL + `/mailinglist/unsubscribe/email/${unsubscribeKey}/${emailHash}`)
            .toPromise();
    }
}
