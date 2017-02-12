<?php

namespace llstarscreamll\Crud\Actions;

use Illuminate\Http\Request;
use llstarscreamll\Crud\Traits\DataGenerator;
use llstarscreamll\Crud\Traits\FolderNamesResolver;

/**
 * PortoFoldersGeneration Class.
 *
 * @author Johan Alvarez <llstarscreamll@hotmail.com>
 */
class CreateActionsFilesAction
{
    use FolderNamesResolver;
    use DataGenerator;

    /**
     * Container name to generate.
     *
     * @var string
     */
    public $container;

    /**
     * Container entity to generate (database table name).
     *
     * @var string
     */
    public $tableName;

    /**
     * The actions files to generate.
     *
     * @var array
     */
    public $files = [
        'ListAndSearch',
        'Create',
        'Update',
        'Delete',
        'Restore',
    ];

    /**
     * Create new CreateModelAction instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->container = studly_case($request->get('is_part_of_package'));
        $this->tableName = $this->request->get('table_name');
    }

    /**
     * @return bool
     */
    public function run()
    {
        foreach ($this->files as $file) {
            $plural = ($file == "ListAndSearch") ? true : false;

            $actionFile = $this->actionsFolder().'/'.$this->actionFile($file, $plural);
            $template = $this->templatesDir().'.Porto/Actions/'.$file;

            $content = view($template, ['gen' => $this]);

            file_put_contents($actionFile, $content) === false
                ? session()->push('error', "Error creating $file action file")
                : session()->push('success', "$file action creation success");
        }

        return true;
    }
}
