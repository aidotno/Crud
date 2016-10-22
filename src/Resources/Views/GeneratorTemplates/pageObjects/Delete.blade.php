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

class {{$test}} extends Index
{
    /**
     * El botón de mover a la papelera.
     *
     * @var array
     */
    static $deleteBtn = 'Mover a Papelera';
    static $deleteBtnElem = 'button.btn.btn-danger';

    /**
     * El botón de confirmación para mover a la papelera.
     *
     * @var array
     */
    static $deleteBtnConfirm = 'Confirmar';
    static $deleteBtnConfirmElem = 'form[name=delete-{{$gen->getDashedModelName()}}-form] .btn.btn-danger';

    /**
     * Mensaje de éxito al eliminar un registro.
     *
     * @var array
     */
    static $msgSuccess = '{{ $gen->getDestroySuccessMsgSingle() }}';
    static $msgSuccessElem = '{{ config('modules.CrudGenerator.uimap.alert-success-selector') }}';

    public function __construct(FunctionalTester $I)
    {
        parent::__construct($I);
    }
}