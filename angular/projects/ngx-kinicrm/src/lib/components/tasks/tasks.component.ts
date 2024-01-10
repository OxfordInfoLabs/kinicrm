import {Component, Input, OnInit} from '@angular/core';
import moment from 'moment';
import _ from 'lodash';
import {AuthenticationService, UserService} from 'ng-kiniauth';
import {MetadataService} from '../../services/metadata.service';
import {BehaviorSubject, merge, Subject} from 'rxjs';
import {debounceTime, map, switchMap} from 'rxjs/operators';
import {TaskService} from '../../services/task.service';
import {OrganisationService} from '../../services/organisation.service';
import {ContactService} from '../../services/contact.service';

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
    public newTask: any = {dueDate: moment().format('YYYY-MM-DD')};
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
    public hideCompleted = false;
    public selectedContact: any;
    public selectedOrganisation: any;
    public organisationSearch = new BehaviorSubject('');
    public organisations: any = [];
    public contacts: any = [];
    public contactSearch = new BehaviorSubject('');

    private reload = new Subject();
    private filters: any = {};
    private loggedInUser: any = null;

    constructor(private userService: UserService,
                private metadataService: MetadataService,
                private taskService: TaskService,
                private authService: AuthenticationService,
                private organisationService: OrganisationService,
                private contactService: ContactService) {
    }

    async ngOnInit() {
        this.loggedInUser = this.authService.authUser.getValue();
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

        merge(this.organisationSearch)
            .pipe(
                debounceTime(300),
                // distinctUntilChanged(),
                switchMap(() =>
                    this.loadOrganisations()
                )
            ).subscribe((organisations: any) => {
            this.organisations = organisations;
        });

        merge(this.contactSearch)
            .pipe(
                debounceTime(300),
                // distinctUntilChanged(),
                switchMap(() =>
                    this.loadContacts()
                )
            ).subscribe((contacts: any) => {
            this.contacts = contacts;
        });

        this.searchText.subscribe(() => {
            this.page = 1;
            this.offset = 0;
        });

        const users: any = await this.userService.getAdminUsers('', 1000, 0).toPromise();
        this.users = _.map(users.results || [], (user: any) => {
            return {
                id: user.id,
                name: user.name,
                emailAddress: user.emailAddress,
                status: user.status
            }
        });

        this.priorities = await this.metadataService.getReferenceTypes('Priority');
        this.newTask.priority = this.priorities[0];
        this.statuses = await this.metadataService.getReferenceTypes('Status');
        this.newTask.status = this.statuses[0];
    }

    public updateOrganisation(event: any) {
        this.selectedOrganisation = event.source.value;
    }

    public updateContact(event: any) {
        this.selectedContact = event.source.value;
    }

    public ticketView(event: any) {
        delete this.filters.assigneeEmail;
        if (event.checked) {
            this.filters.assigneeEmail = this.loggedInUser.emailAddress;
        }
        this.reload.next(Date.now());
    }

    public toggleCompleted() {
        delete this.filters.status;
        if (!this.hideCompleted) {
            this.filters.status = ['In Progress', 'Pending'];
        }
        this.reload.next(Date.now());
    }

    public increaseOffset() {
        this.page = this.page + 1;
        this.offset = (this.limit * this.page) - this.limit;
        this.reload.next(Date.now());
    }

    public decreaseOffset() {
        this.page = this.page <= 1 ? 1 : this.page - 1;
        this.offset = (this.limit * this.page) - this.limit;
        this.reload.next(Date.now());
    }

    public pageSizeChange(value: number) {
        this.limit = value;
        this.reload.next(Date.now());
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
        await this.taskService.updateTask(task);
    }

    public updateTaskName(task: any) {

    }

    public async deleteTask(id: number) {
        const message = 'Are you sure you would like to remove this task?';
        if (window.confirm(message)) {
            await this.taskService.removeTask(id);
            this.reload.next(Date.now());
        }
    }

    public async saveNewTask(newTask: any) {
        let scope = this.scope || 'User';
        let scopeId = this.scopeId || this.loggedInUser.id;
        if (this.selectedOrganisation) {
            scope = 'Organisation';
            scopeId = this.selectedOrganisation.id;
        }
        if (this.selectedContact) {
            scope = 'Contact';
            scopeId = this.selectedContact.id;
        }

        await this.taskService.saveTask(scope, scopeId, newTask);
        this.reload.next(Date.now());
        this.newTask = {
            dueDate: moment().format('YYYY-MM-DD'),
            priority: this.priorities[0],
            status: this.statuses[0]
        };
    }

    private getTasks() {
        this.filters.search = this.searchText.getValue() || '';
        if (this.scope && this.scopeId) {
            this.filters.scope = this.scope;
            this.filters.scopeId = this.scopeId;
        }

        return this.taskService.filterTasks(
            this.filters,
            this.limit,
            this.offset
        ).pipe(map((tasks: any) => {
                return tasks;
            })
        );
    }

    private loadOrganisations() {
        return this.organisationService.searchForOrganisations(
            {search: this.organisationSearch.getValue() || ''}, 1000, 0)
            .pipe(map((organisations: any) => {
                    return organisations;
                })
            );
    }

    private loadContacts() {
        return this.contactService.searchForContacts(
            {search: this.contactSearch.getValue() || ''}, 1000, 0)
            .pipe(map((contacts: any) => {
                    return contacts;
                })
            );
    }
}
