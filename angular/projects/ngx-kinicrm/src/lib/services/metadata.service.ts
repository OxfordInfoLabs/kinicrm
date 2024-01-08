import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {KinicrmModuleConfig} from '../ngx-kinicrm.module';

@Injectable({
    providedIn: 'root'
})
export class MetadataService {

    constructor(private http: HttpClient,
                private config: KinicrmModuleConfig) {
    }

    public searchForTags(searchString = '', limit = 10, offset = 0) {
        const url = this.config.adminHttpURL + '/crm/metadata/tag';
        return this.http.get(url, {
            params: {
                searchString, limit: limit.toString(), offset: offset.toString()
            }
        })
    }

    public searchForCategories(searchString = '', limit = 10, offset = 0) {
        const url = this.config.adminHttpURL + '/crm/metadata/category';
        return this.http.get(url, {
            params: {
                searchString, limit: limit.toString(), offset: offset.toString()
            }
        })
    }

    public getReferenceTypes(type: string) {
        const url = this.config.adminHttpURL + '/crm/metadata/referenceTypes/' + type;
        return this.http.get(url).toPromise()
    }
}
