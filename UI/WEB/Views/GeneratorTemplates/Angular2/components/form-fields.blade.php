import { Component, Input, OnInit } from '@angular/core';
import { FormGroup } from '@angular/forms';

import { {{ $entitySin = $gen->entityName() }} } from './../../models/{{ camel_case($entitySin) }}';

/**
 * {{ $gen->componentClass('form-fields', $plural = false) }} Class.
 *
 * @author [name] <[<email address>]>
 */
{{ '@' }}Component({
  selector: '{{ str_replace(['.ts', '.'], ['', '-'], $gen->componentFile('form-fields', false)) }}',
  templateUrl: './{{ $gen->componentFile('form-fields-html', false) }}'
})
export class {{ $gen->componentClass('form-fields', $plural = false) }} implements OnInit {
  @Input()
  public form: FormGroup;
	
	@Input()
	public formModel: Object;

  @Input()
  public formData: Object;

  @Input()
  public errors: Object;

  @Input()
  public formType: string = 'create';

  constructor() { }

  ngOnInit() { }
}
