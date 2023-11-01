import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OrganisationDialogComponent } from './organisation-dialog.component';

describe('OrganisationDialogComponent', () => {
  let component: OrganisationDialogComponent;
  let fixture: ComponentFixture<OrganisationDialogComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ OrganisationDialogComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OrganisationDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
