import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {KinicrmModuleConfig} from '../ngx-kinicrm.module';

@Injectable({
    providedIn: 'root'
})
export class OrganisationService {

    constructor(private http: HttpClient,
                private config: KinicrmModuleConfig) {
    }


    public searchForOrganisations(filters: any = {}, limit = 10, offset = 0) {
        const url = this.config.adminHttpURL + '/crm/organisation/search?limit=' + limit + '&offset=' + offset;
        return this.http.post(url, filters)
    }

    public getOrganisationFilterValues(memberName: string, filters: any = []) {
        return this.http.post(this.config.adminHttpURL + '/crm/organisation/filterValues/' + memberName, filters);
    }

    public getOrganisation(id: number) {
        return this.http.get(this.config.adminHttpURL + '/crm/organisation/' + id).toPromise();
    }

    public saveOrganisation(address: any) {
        return this.http.post(this.config.adminHttpURL + '/crm/organisation', address).toPromise();
    }

    public deleteOrganisation(organisationId: number) {
        return this.http.delete(this.config.adminHttpURL + '/crm/organisation', {
            params: {organisationId}
        }).toPromise();
    }

    public uploadAttachments(organisationId: number, fileUploads: any) {
        const HttpUploadOptions = {
            headers: new HttpHeaders({'Content-Type': 'file'})
        };
        return this.http.post(this.config.adminHttpURL + '/crm/organisation/attachments/' + organisationId,
            fileUploads, HttpUploadOptions)
            .toPromise();
    }

    public removeAttachment(organisationId: number, attachmentId: number) {
        return this.http.delete(this.config.adminHttpURL + `/crm/organisation/attachments/${organisationId}/${attachmentId}`)
            .toPromise();
    }

    public streamAttachment(id: number) {
        window.open(this.config.accessHttpURL + '/attachment/' + id);
    }
}
