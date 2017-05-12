import { Component, EventEmitter, Input, OnDestroy, OnInit, Output } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { Store } from '@ngrx/store';
import { TranslateService } from '@ngx-translate/core';
import { Router, ActivatedRoute } from '@angular/router';
import { go } from '@ngrx/router-store';

import { FormModelParserService } from './../../../dynamic-form/services/form-model-parser.service';
import * as appMessage from './../../../core/reducers/app-message.reducer';
import * as fromRoot from './../../../reducers';
import * as {{ $reducer = camel_case($gen->entityName()).'Reducer' }} from './../../reducers/{{ $gen->slugEntityName() }}.reducer';
import * as {{ $actions = camel_case($gen->entityName()).'Actions' }} from './../../actions/{{ $gen->slugEntityName() }}.actions';

import { {{ $entitySin = $gen->entityName() }} } from './../../models/{{ camel_case($entitySin) }}';

import { {{ $abstractClass = $gen->componentClass('abstract', false, true) }} } from './{{ str_replace('.ts', '', $gen->componentFile('abstract', false, true)) }}';

/**
 * {{ $gen->componentClass('form', $plural = false) }} Class.
 *
 * @author [name] <[<email address>]>
 */
{{ '@' }}Component({
  selector: '{{ $selector = str_replace(['.ts', '.'], ['', '-'], $gen->componentFile('form', false)) }}',
  templateUrl: './{{ $gen->componentFile('form-html', false) }}',
  exportAs: '{{ str_replace('-component', '', $selector) }}',
})
export class {{ $gen->componentClass('form', $plural = false) }} extends {{ $abstractClass }} implements OnInit, OnDestroy {
  /**
   * {{ ucfirst(str_replace('_', '', $gen->tableName)) }} form group.
   * @type FormGroup
   */
  public form: FormGroup;

  /**
   * Form type to render (create|update|details). Because some fields could not
   * be shown based on the form type.
   * @type string
   */
  @Input()
  public formType: string = 'create';

  /**
   * {{ $gen->componentClass('form', $plural = false) }} constructor.
   */
  public constructor(
    protected store: Store<fromRoot.State>,
    protected activedRoute: ActivatedRoute,
    protected translateService: TranslateService,
    protected formModelParserService: FormModelParserService,
  ) { super(); }

  /**
   * The component is ready, this is called after the constructor and after the
   * first ngOnChanges(). This is invoked only once when the component is
   * instantiated.
   */
  public ngOnInit() {
    this.setupStoreSelects();
    this.initForm();
    this.setupForm();
  }

  /**
   * Parse the form model to form group.
   */
  private setupForm() {
    this.formModelSubscription$ = this.formModel$
      .subscribe((model) => {
        if (model) {
          this.form = this.formModelParserService.toFormGroup(model);

          if (this.formType == 'details' || this.formType == 'edit') {
            this.patchForm();
          } else {
            this.formConfigured = true;
          }
        }
      });
  }

  /**
   * Patch the form group values with the selected item data.
   */
  private patchForm() {
    this.selectedItem$.subscribe(({{ $model = camel_case($gen->entityName()) }}) => {
      if ({{ $model }} != null && {{ $model }}.id && {{ $model }}.id.includes(this.id)) {
        this.form.patchValue({{ $model }});
        this.formConfigured = true;
      }
    });
  }

  /**
   * Hadle the form submition based on the actual form type.
   */
  public submitForm() {
    if (this.formType == 'create')
      this.store.dispatch(new {{ $actions }}.CreateAction(this.form.value));

    if (this.formType == 'edit')
      this.store.dispatch(new {{ $actions }}.UpdateAction(this.form.value));

    if (this.formType == 'details')
      this.store.dispatch(go(['{{ $gen->slugEntityName() }}', this.id, 'edit']));
  }

  /**
   * Clean the component canceling the background taks. This is called before the
   * component instance is funally destroyed.
   */
  public ngOnDestroy() {
    this.formModelSubscription$.unsubscribe();
  }
}
