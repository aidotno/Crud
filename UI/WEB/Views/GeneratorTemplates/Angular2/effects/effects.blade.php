import { Injectable } from '@angular/core';
import { Actions, Effect } from '@ngrx/effects';
import { Action, Store } from '@ngrx/store';
import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';
import { go } from '@ngrx/router-store';
import 'rxjs/add/operator/withLatestFrom'

import * as fromRoot from './../../reducers';
import * as appMsgActions from './../../core/actions/app-message.actions';
import { FormModelParserService } from './../../dynamic-form/services/form-model-parser.service';
import { {{ ($entitySin = $gen->entityName()).'Pagination' }} } from './../models/{{ $camelEntity = camel_case($entitySin) }}Pagination';
import { {{ $entitySin }}Service } from './../services/{{ $gen->slugEntityName() }}.service';
import * as {{ $actions = camel_case($gen->entityName()) }} from './../actions/{{ $gen->slugEntityName() }}.actions';
import { {{ $entitySin = $gen->entityName() }} } from './../models/{{ camel_case($entitySin) }}';
import { AppMessage } from './../../core/models/appMessage';
import { Effects } from './../../core/effects/abstract.effects';

/**
 * {{ $entitySin }}Effects Class.
 *
 * @author [name] <[<email address>]>
 */
@Injectable()
export class {{ $entitySin }}Effects extends Effects {
  
  /**
   * {{ $entitySin }}Effects contructor.
   */
  public constructor(
    private actions$: Actions,
    private {{ $service = camel_case($entitySin).'Service' }}: {{ $entitySin }}Service,
    private FormModelParserService: FormModelParserService,
    private store: Store<fromRoot.State>
  ) { super(); }

  protected setMessages(message: AppMessage): Action {
    return new {{ $actions }}.SetMessagesAction(message);
  }

  @Effect()
  getFormModel$: Observable<Action> = this.actions$
    .ofType({{ $actions }}.GET_FORM_MODEL)
    .withLatestFrom(this.store.select(fromRoot.get{{ $gen->entityName() }}State))
    .switchMap(([action, state]) => {
      // prevent API call if we have the form model already
      if (state.{{ camel_case($gen->entityName()) }}FormModel !== null) {
        return of(new {{ $actions }}.GetFormModelSuccessAction(state.{{ camel_case($gen->entityName()) }}FormModel));
      }

      return this.{{ $service }}.getFormModel()
        .map((data) => this.FormModelParserService.parse(data, this.{{ $service }}.fieldsLangKey))
        .map((data) => { return new {{ $actions }}.GetFormModelSuccessAction(data)})
        .catch((error: AppMessage) => this.handleError(error));
    });

    @Effect()
    getFormData$: Observable<Action> = this.actions$
      .ofType({{ $actions }}.GET_FORM_DATA)
      .withLatestFrom(this.store.select(fromRoot.get{{ $gen->entityName() }}State))
      .switchMap(([action, state]) => {
        // prevent API call if we have the form data already
        if (state.{{ camel_case($gen->entityName()) }}FormData !== null) {
          return of(new {{ $actions }}.GetFormDataSuccessAction(state.{{ camel_case($gen->entityName()) }}FormData));
        }

        return this.{{ $service }}.getFormData()
          .map((data) => { return new {{ $actions }}.GetFormDataSuccessAction(data)})
          .catch((error: AppMessage) => this.handleError(error));
      });

  @Effect()
  setSearchQuery$: Observable<Action> = this.actions$
    .ofType({{ $actions }}.SET_SEARCH_QUERY)
    .map((action: Action) => action.payload)
    .map((searchQuery) => new {{ $actions }}.LoadAction());

  @Effect()
  load$: Observable<Action> = this.actions$
    .ofType({{ $actions }}.LOAD)
    .map((action: Action) => action.payload)
    .withLatestFrom(this.store.select(fromRoot.get{{ $gen->entityName() }}State))
    .switchMap(([action, state]) => {
      return this.{{ $service }}.load(state.searchQuery)
        .map((data: {{ $entitySin.'Pagination' }}) => { return new {{ $actions }}.LoadSuccessAction(data)})
        .catch((error: AppMessage) => this.handleError(error));
    });

    @Effect()
    get$: Observable<Action> = this.actions$
      .ofType({{ $actions }}.GET)
      .withLatestFrom(this.store.select(fromRoot.get{{ $gen->entityName() }}State))
      .switchMap(([action, state]) => {
        // prevent API call if we have the data object already
        if (state.selected{{ $gen->entityName() }} && action.payload == state.selected{{ $gen->entityName() }}.id) {
          return of(new {{ $actions }}.SetSelectedAction(state.selected{{ $gen->entityName() }}));
        }

        return this.{{ $service }}.get(action.payload)
          .mergeMap((data: {{ $entitySin }}) => {
            return [
              new {{ $actions }}.SetSelectedAction(data),
            ];
          })
          .catch((error: AppMessage) => this.handleError(error));
      });

    @Effect()
    create$: Observable<Action> = this.actions$
      .ofType({{ $actions }}.CREATE)
      .map((action: Action) => action.payload)
      .switchMap((data) => {
        return this.{{ $service }}.create(data)
          .mergeMap((data: {{ $entitySin }}) => {
            return [
              new {{ $actions }}.SetSelectedAction(data),
              new {{ $actions }}.SetMessagesAction(this.{{ $service }}.getMessage('create')),
              go(['{{ $gen->slugEntityName() }}', data.id, 'details'])
            ];
          })
          .catch((error: AppMessage) => this.handleError(error));
      });

    @Effect()
    update$: Observable<Action> = this.actions$
      .ofType({{ $actions }}.UPDATE)
      .map((action: Action) => action.payload)
      .switchMap((data: {{ $entitySin }}) => {
        return this.{{ $service }}.update(data)
          .mergeMap((data: {{ $entitySin }}) => {
            return [
              new {{ $actions }}.SetSelectedAction(data),
              new {{ $actions }}.SetMessagesAction(this.{{ $service }}.getMessage('update')),
              go(['{{ $gen->slugEntityName() }}', data.id, 'details'])
            ];
          })
          .catch((error: AppMessage) => this.handleError(error));
      });

    @Effect()
    delete$: Observable<Action> = this.actions$
      .ofType({{ $actions }}.DELETE)
      .map((action: Action) => action.payload)
      .switchMap(action => {
        return this.{{ $service }}.delete(action.id)
          .mergeMap(() => {
            let actions = [
              new {{ $actions }}.SetMessagesAction(this.{{ $service }}.getMessage('delete')),
              go(['{{ $gen->slugEntityName() }}'])
            ];

            if(action.reloadListQuery) {
              actions.push(new {{ $actions }}.LoadAction(action.reloadListQuery));
            }

            return actions;
          })
          .catch((error: AppMessage) => this.handleError(error));
      });
}
