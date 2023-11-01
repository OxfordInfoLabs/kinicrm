import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {KinicrmModuleConfig} from '../ngx-kinicrm.module';

@Injectable({
    providedIn: 'root'
})
export class AddressService {

    constructor(private http: HttpClient,
                private config: KinicrmModuleConfig) {
    }

    public searchForAddresses(searchString = '', limit = 10, offset = 0) {
        const url = this.config.adminHttpURL + '/crm/address';
        return this.http.get(url, {
            params: {
                searchString, limit: limit.toString(), offset: offset.toString()
            }
        })
    }

    public getAddress(id: number) {
        return this.http.get(this.config.adminHttpURL + '/crm/address/' + id).toPromise();
    }

    public saveAddress(address: any) {
        return this.http.post(this.config.adminHttpURL + '/crm/address', address).toPromise();
    }

    public deleteAddress(addressId: number) {
        return this.http.delete(this.config.adminHttpURL + '/crm/address', {
            params: {addressId}
        }).toPromise();
    }
}
