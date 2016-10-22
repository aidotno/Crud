<?php
/* @var $gen llstarscreamll\CrudGenerator\Providers\TestsGenerator */
/* @var $fields [] */
/* @var $test [] */
/* @var $request Request */
?>
<?='<?php'?>


<?= $gen->getClassCopyRightDocBlock() ?>


namespace Page\Functional\{{$gen->studlyCasePlural()}};

use FunctionalTester;
@if($request->has('use_faker'))
use Faker\Factory as Faker;
@endif

class {{$test}} extends Index
{
    /**
     * La URL de la página.
     *
     * @var string
     */
    public static $URL = '/{{$gen->route()}}/create';

    /**
     * El título de la página.
     *
     * @var string
     */
    static $title = 'Crear';

    /**
     * El selector del formulario.
     *
     * @var string
     */
    static $form = 'form[name=create-{{$gen->getDashedModelName()}}-form]';

    /**
     * El botón submit del formulario.
     *
     * @var array
     */
    static $formBtnElem = '{{config('modules.CrudGenerator.uimap.create-form-button-selector')}}';

    /**
     * Mensaje de éxito al crear un registro.
     *
     * @var array
     */
    static $msgSuccess = '{{ $gen->getStoreSuccessMsg() }}';
    static $msgSuccessElem = '{{ config('modules.CrudGenerator.uimap.alert-success-selector') }}';

    public function __construct(FunctionalTester $I)
    {
        parent::__construct($I);
    }
}