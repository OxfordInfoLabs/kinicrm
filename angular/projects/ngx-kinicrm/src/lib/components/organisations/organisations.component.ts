import {Component, OnInit} from '@angular/core';
import {BehaviorSubject, merge, Subject} from 'rxjs';
import {OrganisationService} from '../../services/organisation.service';
import {debounceTime, map, switchMap} from 'rxjs/operators';

@Component({
  selector: 'kcrm-organisations',
  templateUrl: './organisations.component.html',
  styleUrls: ['./organisations.component.css']
})
export class OrganisationsComponent implements OnInit {

    public organisations: any = [];
    public searchText = new BehaviorSubject('');
    public limit = 10;
    public offset = 0;
    public page = 1;
    public endOfResults = false;
    public loading = true;

    private reload = new Subject();

    constructor(private organisationService: OrganisationService) {
    }

    async ngOnInit() {
        merge(this.searchText, this.reload)
            .pipe(
                debounceTime(300),
                // distinctUntilChanged(),
                switchMap(() =>
                    this.getOrganisations()
                )
            ).subscribe((organisations: any) => {
            this.endOfResults = organisations.length < this.limit;
            this.organisations = organisations;
            this.loading = false;
        });

        this.searchText.subscribe(() => {
            this.page = 1;
            this.offset = 0;
        });
    }

    public increaseOffset() {
        this.page = this.page + 1;
        this.offset = (this.limit * this.page) - this.limit;

    }

    public decreaseOffset() {
        this.page = this.page <= 1 ? 1 : this.page - 1;
        this.offset = (this.limit * this.page) - this.limit;
    }

    public pageSizeChange(value: number) {
        this.limit = value;
    }

    private getOrganisations() {
        return this.organisationService.searchForOrganisations(
            this.searchText.getValue() || '',
            this.limit,
            this.offset
        ).pipe(map((feeds: any) => {
                return feeds;
            })
        );
    }
}
