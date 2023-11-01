import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {KinicrmModuleConfig} from '../ngx-kinicrm.module';

@Injectable({
    providedIn: 'root'
})
export class OrganisationService {

    constructor(private http: HttpClient,
                private config: KinicrmModuleConfig) {
    }


    public searchForOrganisations(searchString = '', limit = 10, offset = 0) {
        const url = this.config.adminHttpURL + '/crm/organisation';
        return this.http.get(url, {
            params: {
                searchString, limit: limit.toString(), offset: offset.toString()
            }
        })
    }

    public getOrganisation(id: number) {
        return this.http.get(this.config.adminHttpURL + '/crm/organisation/' + id).toPromise();
    }

    public saveOrganisation(address: any) {
        return this.http.post(this.config.adminHttpURL + '/crm/organisation', address).toPromise();
    }

    public deleteOrganisation(addressId: number) {
        return this.http.delete(this.config.adminHttpURL + '/crm/organisation', {
            params: {addressId}
        }).toPromise();
    }
}
