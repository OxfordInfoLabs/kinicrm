import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {KinicrmModuleConfig} from '../ngx-kinicrm.module';

@Injectable({
    providedIn: 'root'
})
export class TaskService {

    constructor(private http: HttpClient,
                private config: KinicrmModuleConfig) {
    }

    public getTask(id: number) {
        return this.http.get(this.config.adminHttpURL + '/crm/task/' + id).toPromise();
    }

    public filterTasks(filters: any = {}, limit = 10, offset = 0) {
        const url = this.config.adminHttpURL + '/crm/task/search?limit=' + limit + '&offset=' + offset;
        return this.http.post(url, filters)
    }

    public saveTask(scope: string, scopeId: number, taskItem: any) {
        const url = this.config.adminHttpURL + `/crm/task/${scope}/${scopeId}`;
        return this.http.post(url, taskItem).toPromise();
    }

    public removeTask(taskId: number) {
        return this.http.delete(this.config.adminHttpURL + '/crm/task', {
            params: {taskId}
        }).toPromise();
    }
}
