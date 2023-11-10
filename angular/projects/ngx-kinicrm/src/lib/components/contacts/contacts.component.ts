import {Component, OnInit} from '@angular/core';
import {BehaviorSubject, merge, Subject} from 'rxjs';
import {Router} from '@angular/router';
import {debounceTime, map, switchMap} from 'rxjs/operators';
import {ContactService} from '../../services/contact.service';
import * as _ from 'lodash';

@Component({
  selector: 'kcrm-contacts',
  templateUrl: './contacts.component.html',
  styleUrls: ['./contacts.component.css']
})
export class ContactsComponent implements OnInit {

    public contacts: any = [];
    public searchText = new BehaviorSubject('');
    public limit = 25;
    public offset = 0;
    public page = 1;
    public endOfResults = false;
    public loading = true;
    public showFilters = false;

    public filterConfig: any = [
        {label: 'Tags', member: 'tags', memberFilter: []},
        {label: 'Categories', member: 'categories', memberFilter: []}
    ];

    private reload = new Subject();
    private filters: any = {tags: [], categories: []};

    constructor(private router: Router,
                private contactService: ContactService) {
    }

    async ngOnInit(){
        merge(this.searchText, this.reload)
            .pipe(
                debounceTime(300),
                // distinctUntilChanged(),
                switchMap(() =>
                    this.getContacts()
                )
            ).subscribe((contacts: any) => {
            this.endOfResults = contacts.length < this.limit;
            this.contacts = contacts;
            this.loading = false;
            this.loadFilters();
        });

        this.searchText.subscribe(() => {
            this.page = 1;
            this.offset = 0;
        });
    }

    public updateFilter(config: any) {
        this.filters[config.member] = config.memberFilter.filter((filter: any) => {
            return filter.checked;
        }).map((filter: any) => {
            return filter.memberValue;
        });

        this.reload.next(Date.now());

        this.loadFilters();
    }

    public editContact(contact?: any) {
        const id = contact ? contact.id : 0;
        this.router.navigate(['/crm', 'contacts', id]);
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

    private getContacts() {
        this.filters.search = this.searchText.getValue() || '';
        return this.contactService.searchForContacts(
            this.filters,
            this.limit,
            this.offset
        ).pipe(map((contacts: any) => {
                return contacts;
            })
        );
    }

    private async loadFilters() {
        for (const config of this.filterConfig) {

            const filters: any = await this.contactService.getContactFilterValues(config.member, this.filters)
                .toPromise();

            filters.forEach((filter: any) => {
                const existing: any = _.find(config.memberFilter, {memberValue: filter.memberValue});
                if (existing) {
                    existing.expressionValue = filter.expressionValue;
                } else {
                    config.memberFilter.push(filter);
                }
            });

            _.remove(config.memberFilter, (filter: any) => {
                return !_.find(filters, {memberValue: filter.memberValue});
            });
        }
    }
}
