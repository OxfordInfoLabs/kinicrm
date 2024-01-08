import {Component, Input, OnInit} from '@angular/core';
import moment from 'moment';
import _ from 'lodash';
import {UserService} from 'ng-kiniauth';
import {MetadataService} from '../../services/metadata.service';
import {BehaviorSubject, merge, Subject} from 'rxjs';
import {debounceTime, map, switchMap} from 'rxjs/operators';
import {TaskService} from '../../services/task.service';

@Component({
  selector: 'kcrm-tasks',
  templateUrl: './tasks.component.html',
  styleUrls: ['./tasks.component.css']
})
export class TasksComponent implements OnInit {

    @Input() scope: string = '';
    @Input() scopeId: number = 0;

    public users: any = [];
    public priorities: any = [];
    public statuses: any = [];
    public newTask: any = {};
    public tasks: any = [];
    public moment = moment;
    public _ = _;
    public searchText = new BehaviorSubject('');
    public limit = 25;
    public offset = 0;
    public page = 1;
    public endOfResults = false;
    public loading = true;
    public showFilters = false;

    private reload = new Subject();
    private filters: any = {};

    constructor(private userService: UserService,
                private metadataService: MetadataService,
                private taskService: TaskService) {
    }

    async ngOnInit() {
        merge(this.searchText, this.reload)
            .pipe(
                debounceTime(300),
                // distinctUntilChanged(),
                switchMap(() =>
                    this.getTasks()
                )
            ).subscribe((tasks: any) => {
            this.endOfResults = tasks.length < this.limit;
            this.tasks = tasks;
            this.loading = false;
            // this.loadFilters();
        });

        this.searchText.subscribe(() => {
            this.page = 1;
            this.offset = 0;
        });

        const users: any = await this.userService.getAdminUsers().toPromise();
        this.users = _.map(users.results || [], (user: any) => {
            return {
                id: user.id,
                name: user.name,
                emailAddress: user.emailAddress,
                status: user.status
            }
        });

        this.priorities = await this.metadataService.getReferenceTypes('Priority');
        this.statuses = await this.metadataService.getReferenceTypes('Status');
    }

    public trackByFn(index: number, item: any) {
        return index;
    }

    public bindSelectValue(v1: any, v2: any) {
        if (v1 && v2 && v1.id === v2.id) {
            return v1.name;
        }
        return '';
    }

    public editTask(taskId: number) {

    }

    public async updateTaskItem(task: any) {
        await this.taskService.saveTask(this.scope, this.scopeId, task);
    }

    public updateTaskName(task: any) {

    }

    public deleteTask(id: number) {

    }

    public async saveNewTask(newTask: any) {
        await this.taskService.saveTask(this.scope, this.scopeId, newTask);
        this.reload.next(Date.now());
        this.newTask = {};
    }

    private getTasks() {
        this.filters.search = this.searchText.getValue() || '';
        this.filters.scope = this.scope;
        this.filters.scopeId = this.scopeId;
        return this.taskService.filterTasks(
            this.filters,
            this.limit,
            this.offset
        ).pipe(map((tasks: any) => {
                return tasks;
            })
        );
    }
}
