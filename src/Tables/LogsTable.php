<?php

namespace TomatoPHP\TomatoLogs\Tables;

use Illuminate\Http\Request;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use TomatoPHP\TomatoBackup\Models\BackupDestination;
use TomatoPHP\TomatoLogs\Models\LogFile;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination as SpatieBackupDestination;

class LogsTable extends AbstractTable
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return LogFile::query();
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $table
            ->withGlobalSearch(label: trans('tomato-admin::global.search'),columns: ['id','path'])
            ->bulkAction(
                label: trans('tomato-admin::global.crud.delete'),
                each: function (BackupDestination $model) {
                    SpatieBackupDestination::create($model->disk, config('backup.backup.name'))
                        ->backups()
                        ->first(function (Backup $backup) use ($model) {
                            return $backup->path() === $model->path;
                        })
                        ->delete();

                    sleep(5);
                },
                after: fn () => Toast::danger(trans('tomato-backup::global.delete_backup'))->autoDismiss(2),
                confirm: true
            )
            ->export()
            ->defaultSort('id')
            ->column(key: 'actions',label: trans('tomato-roles::global.roles.actions'))
            ->column(key: "id",label: trans('tomato-logs::global.id'), sortable: true, hidden: true)
            ->column(key: "name",label: trans('tomato-logs::global.name'), sortable: true)
            ->paginate(15);
    }
}
