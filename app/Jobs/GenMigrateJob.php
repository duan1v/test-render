<?php

namespace App\Jobs;

use App\Enums\FieldTypes;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class GenMigrateJob
 * @package App\Jobs
 * @version 2023/10/21 0021, 19:46
 *
 */
class GenMigrateJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    const MIGRATE_DELETE = <<<'PHP'
            $table->dateTime('deleted_at')->nullable();
PHP;
    const MIGRATE_FIELD = <<<'PHP'
            $table->%s('%s')%s->comment('%s');
PHP;

    const MIGRATE_FILE = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('%s', function (Blueprint $table) {
            $table->id();
%s
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('%s');
    }
};

PHP;
    const MODEL_FILE = <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class %s
 * @package App\Models
 *
 * @property int id
%s
 * @property string created_at
 * @property string updated_at
 *
 */
class %s extends Model
{
    const COLUMNS = [
        'id' => ['title' => 'ID', 'name' => 'ID'],
%s
        'created_at' => ['title' => 'Created at', 'name' => 'Created at'],
    ];

    protected $table = "%s";

    protected $fillable = [
%s
        "created_at",
        "updated_at",
    ];
}


PHP;
    const CONTROLLER_FILE = <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\%s;
use App\Models\MigDmsDeclaration;
use Illuminate\Http\Request;
/**
 * Class %s
 * @package App\Http\Controllers
 *
 */
class %s extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page');
        $search = $request->get('search');
        return view('%s.index', [
            'colsOrderCookieName'    => 'colsOrder%s',
            'selectedColsCookieName' => 'selectedCols%s',
            'limitCookieName'        => 'limit%s',
            'columns'                => %s::COLUMNS,
            'page'                   => $page,
            'search'                 => $search,
        ]);
    }

    public function show($did, Request $request)
    {
        $d = %s::query()->find($did);
        $validations = MigDmsDeclaration::getValidations();
        $columns = %s::COLUMNS;
        $page = $request->get('page');
        $search = $request->get('search');
        return view('%s.show', compact('d', 'columns', 'validations', 'page', 'search'));
    }
}

PHP;
    const BLADE_FILE = <<<'PHP'
@extends('layouts/tab-filter')

@section('style')
    <style>
        pre {
            background-color: #282c34;
        }

        .popover {
            --falcon-popover-max-width: auto !important;
        }

        .custom-popover .popover-body {
            white-space: pre-line;
        }

        .vertical-border {
            border-collapse: collapse;
        }

        .vertical-border td, .vertical-border th {
            border-left: 1px solid black;
            padding: 8px;
        }

        #table-list-body tr td {
            white-space: nowrap;
        }

        #codeListContent table {
            border: 1px solid black;
        }

        #codeListContent td, #codeListContent th {
            border-left: 1px solid black;
            padding: 8px;
        }
    </style>
@endsection
@section('list-header')
    <livewire:filter-b/>
@endsection

@section('list-table')
    <livewire:%ss :page="$page" :search="$search" :selectedColsCookieName="$selectedColsCookieName" :colsOrderCookieName="$colsOrderCookieName" :limitCookieName="$limitCookieName"/>
@endsection

@section('list-filter')
    <livewire:filter-a :selectedColsCookieName="$selectedColsCookieName" :colsOrderCookieName="$colsOrderCookieName" :columns="$columns"/>
@endsection

PHP;

    const BLADE_SHOW_FILE = <<<'PHP'
@extends('layouts/show')

@section('second-style')
    <style>
        pre {
            background-color: #282c34;
        }

        pre code {
            padding-top: 0 !important;
        }

        pre:before {
            content: "";
            display: block;
            background: url({{asset('falcon/assets/img/sd.png')}});
            height: 32px;
            width: 100%;
            background-size: 40px;
            background-repeat: no-repeat;
            background-color: #384548;
            margin-bottom: 0;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            background-position: 10px 10px;
        }

        .popover {
            --falcon-popover-max-width: auto !important;
        }

        .custom-popover .popover-body {
            /*width: auto;*/
            /*white-space: nowrap;*/
        }

        #codeListContent table {
            border: 1px solid black;
        }

        #codeListContent td, #codeListContent th {
            border-left: 1px solid black;
            padding: 8px;
        }
    </style>
@endsection

@section('content')
    <link href="{{ asset('falcon') }}/vendors/highlight/styles/atom-one-dark.min.css" rel="stylesheet">
    <script src="{{ asset('falcon') }}/vendors/highlight/highlight.min.js"></script>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        {!! request()->header(\App\Constants\HeaderConstant::FOURTH_MENU_DISPLAY,'') !!}
    </ul>
    <div class="card mb-3" style="margin-top: 1em;">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <div class="d-flex d-flex align-items-center">
                        <div class="flex-1 d-flex justify-content-start align-items-center">
                            <div>
                                @foreach(['attribute_path','attribute_name','xpath'] as $column)
                                    <h5 class="fs-1 mb-1 me-2"
                                        data-bs-custom-class="custom-popover"
                                        data-bs-trigger="hover focus"
                                        data-bs-toggle="popover" data-bs-placement="top"
                                        data-bs-content="{{data_get($columns,$column.'.title')}}.">
                                        {{data_get($columns,$column.'.name')}} :
                                    </h5>
                                @endforeach
                            </div>
                            <div>
                                @foreach(['attribute_path','attribute_name','xpath'] as $column)
                                    <h5 class="fs-1 mb-1">
                                        {!! data_get($d, $column)?:'&nbsp;' !!}
                                    </h5>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <a class="btn btn-link text-secondary p-0 me-3 fw-medium discard-change" role="button"
                       href="{{route('user-manual.declarations',['page'=>$page,'search'=>$search])}}" target="_blank">
                        {{trans('lan.back')}} to list >
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 data-bs-trigger="hover focus"
                data-bs-toggle="popover" data-bs-placement="top"
                data-bs-content="{{data_get($columns,'de_name_number.title')}}."
                class="mb-0 d-inline col-auto">Validation</h6>
        </div>
        <div class="card-body">
            <div class="row gx-2">
                <?php $columnVal = data_get($d, 'validation') ?>
                @if($columnVal)
                    @foreach(explode(',',$columnVal) as $k => $val)
                        <div class="col-12 mb-3 row">
                            <h6>{{$val}}</h6>
                            <div>
<pre><code class="python">
{{pseudocodeFormat(data_get($validations,$val,''))}}
</code></pre>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="row g-0">
        <div class="col-lg-4 pe-lg-2">
            <div class="sticky-sidebar">
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">{{trans('lan.basic_info')}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gx-2">
                            @foreach(['id','seq_no','occurrences_number_min','occurrences_number_max',"class_id","unique_class_id","unique_attr_id",] as $column)
                                <div class="col-12 mb-3 row">
                                    <div class="col-5 mx-0 px-0">
                                        <span
                                            data-bs-trigger="hover focus"
                                            data-bs-toggle="popover" data-bs-placement="top"
                                            data-bs-content="{{data_get($columns,$column.'.title')}}."
                                            class="me-2 mb-0 fw-bold fs--1">{{data_get($columns,$column.'.name')}}:</span>
                                    </div>
                                    <div class="col-7 mx-0 px-0">
                                        <span class="fs--1">{{data_get($d, $column)}}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 data-bs-trigger="hover focus"
                            data-bs-toggle="popover" data-bs-placement="top"
                            data-bs-content="R to indicate REQUIRED,O to indicate OPTIONAL,N to indicate NOT USED."
                            class="mb-0 d-inline col-auto">Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gx-2">
                            @foreach(['status_b1_515','status_b2_515','status_b3_515','status_b4_515','status_c1_515','status_c2_511','status_h1_415','status_h2_415',] as $column)
                                <div class="col-6 mb-3 row">
                                    <div class="col-6 mx-0 px-0">
                                        <span
                                            data-bs-trigger="hover focus"
                                            data-bs-toggle="popover" data-bs-placement="top"
                                            data-bs-content="{{data_get($columns,$column.'.title')}}."
                                            class="me-2 mb-0 fw-bold fs--1">{{data_get($columns,$column.'.name')}}:</span>
                                    </div>
                                    <div class="col-6 mx-0 px-0">
                                        <span class="fs--1">{{data_get($d, $column)}}</span>
                                    </div>
                                </div>
                            @endforeach
                            @foreach(['status_h3_415','status_h4_415','status_h5_415','status_h6_415','status_i1_415','status_i2_432','status_i2a_432_eidr_min','status_i2b_432_eidr_max'] as $column)
                                <div class="col-6 mb-3 row">
                                    <div class="col-6 mx-0 px-0">
                                        <span
                                            data-bs-trigger="hover focus"
                                            data-bs-toggle="popover" data-bs-placement="top"
                                            data-bs-content="{{data_get($columns,$column.'.title')}}."
                                            class="me-2 mb-0 fw-bold fs--1">{{data_get($columns,$column.'.name')}}:</span>
                                    </div>
                                    <div class="col-6 mx-0 px-0">
                                        <span class="fs--1">{{data_get($d, $column)}}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 ps-lg-2">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 data-bs-trigger="hover focus"
                        data-bs-toggle="popover" data-bs-placement="top"
                        data-bs-content="{{data_get($columns,'code_list.title')}}."
                        class="mb-0 d-inline col-auto">Code List</h6>
                </div>
                <div class="card-body">
                    <div class="row gx-2">
                        <?php $columnVal = data_get($d, 'code_list') ?>
                        @if($columnVal)
                            <div class="col-12 mb-3">
                                @foreach(explode(',',$columnVal) as $k => $val)
                                    <span class="cursor-pointer d-inline" data-bs-toggle="modal"
                                          data-code-tag='{{$val}}'
                                          data-bs-target="#codeList">{{$val}}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @foreach(['wco_format',"definition",'format','remarks','de_name_number'] as $column)
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6
                            data-bs-trigger="hover focus"
                            data-bs-toggle="popover" data-bs-placement="top"
                            data-bs-content="{{data_get($columns,$column.'.title')}}."
                            class="mb-0 d-inline col-auto">{{data_get($columns,$column.'.name')}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row gx-2">
                            <span class="fs--1">{{data_get($d, $column)}}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <style>
        .iti {
            display: block;
        }

        .choices__list--dropdown {
            z-index: 2;
        }
    </style>
    <script src="{{ asset('falcon') }}/vendors/highlight/languages/python.min.js"></script>
    <script>hljs.highlightAll();</script>
    <livewire:code/>
@endsection

PHP;

    const ROUTE_FILE = <<<'PHP'
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/%s/index', [
    \App\Http\Controllers\%s::class,
    'index'
])->name('%s.index');
Route::get('/%s/show/{did}', [
    \App\Http\Controllers\%s::class,
    'show'
])->name('%s.show');

PHP;
    const LIVEWIRE_FILE = <<<'PHP'
<?php

namespace App\Http\Livewire;

use App\Exports\CollectionExport;
use App\Models\%s;
use App\Models\MigDmsDeclaration;
use Maatwebsite\Excel\Facades\Excel;

class %ss extends MigDmsDeclarations
{
    public $columns = %s::COLUMNS;

    public $showUrl = '%s.show';

    protected function genRows()
    {
        return %s::query()
            ->select([...$this->selectedCols, 'id'])
            ->where(function ($query) {
                $query->where('attribute_name', 'like', '%%' . $this->search . '%%')
                    ->orWhere('attribute_path', 'like', '%%' . $this->search . '%%');
            })
            ->orderBy('id')
            ->paginate($this->limit ?: 15);
    }

    public function handelExport()
    {
        $rows = %s::query()
            ->select(array_keys($this->columns))
            ->where(function ($query) {
                $query->where('attribute_name', 'like', '%%' . $this->search . '%%')
                    ->orWhere('attribute_path', 'like', '%%' . $this->search . '%%');
            })
            ->orderBy('id')
            ->get();
        return Excel::download(new CollectionExport($rows, array_column($this->columns, 'name')), '%s.xlsx');
    }
}


PHP;


    public function __construct(public $step1Data, public $tableName)
    {
    }

    /**
     * 最大重试次数
     *
     * @var int
     */
    public int $tries = 3;

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle(): bool
    {
        try {
            $this->genControllerFile();
            $this->genMigrateFile();
            $this->genModelFile();
            return true;
        } catch (Exception $e) {
            Log::error(__METHOD__, [$e]);
            return false;
        }
    }

    public function genMigrateFile()
    {
        $filename = sprintf("%s_create_%s_table.php", now()->format('Y_m_d_His'), $this->tableName);
        $ts = [];
        foreach ($this->step1Data as $datum) {
            $type = data_get($datum, 'type');
            $ts[] = sprintf(self::MIGRATE_FIELD, $type, data_get($datum, 'name'), FieldTypes::fromLength($type)->value, data_get($datum, 'comment'));
        }
        $mf = sprintf(self::MIGRATE_FILE, $this->tableName, implode("\n", $ts), $this->tableName);
        $path = base_path('database/migrations/' . $filename);
        file_put_contents($path, $mf);
    }

    public function baseClassName()
    {
        return preg_replace('/s$/', '', ucfirst(Str::camel($this->tableName)));
    }

    public function genControllerFile()
    {
        $bladeDirName = str_replace('_', '-', preg_replace('/s$/', '', $this->tableName));
        $modelClassname = $this->baseClassName();
        $classname = sprintf("%sController", $this->baseClassName());
        $filename = sprintf("%s.php", $classname);

        $suffix = preg_replace('/[^A-Z]/', '', $modelClassname);;
        $f = sprintf(self::CONTROLLER_FILE, $modelClassname, $classname, $classname, $bladeDirName, $suffix, $suffix, $suffix, $modelClassname, $modelClassname, $modelClassname, $bladeDirName);
        $path = base_path('app/Http/Controllers/' . $filename);
        file_put_contents($path, $f);

        $fr = sprintf(self::ROUTE_FILE, $bladeDirName, $classname, $bladeDirName, $bladeDirName, $classname, $bladeDirName);
        $routePath = base_path(sprintf('routes/%s.php', $bladeDirName));
        file_put_contents($routePath, $fr);

        $bladeFolderPath = base_path(sprintf('resources/views/%s', $bladeDirName));
        if (!file_exists($bladeFolderPath)) {
            mkdir($bladeFolderPath, 0777, true);
        }
        $bladePath = base_path(sprintf('resources/views/%s/index.blade.php', $bladeDirName));
        $fb = sprintf(self::BLADE_FILE, $bladeDirName);
        file_put_contents($bladePath, $fb);
        $bladeShowPath = base_path(sprintf('resources/views/%s/show.blade.php', $bladeDirName));
        file_put_contents($bladeShowPath, self::BLADE_SHOW_FILE);

        $liveWirePath = base_path(sprintf('app/Http/Livewire/%ss.php', $modelClassname));
        $fl = sprintf(self::LIVEWIRE_FILE, $modelClassname, $modelClassname, $modelClassname, $bladeDirName, $modelClassname, $modelClassname, $this->tableName);
        file_put_contents($liveWirePath, $fl);
    }

    public function genModelFile()
    {
        $classname = $this->baseClassName();
        $filename = sprintf("%s.php", $classname);
        $ts1 = [];
        $ts2 = [];
        $ts3 = [];
        foreach ($this->step1Data as $datum) {
            $type = data_get($datum, 'type');
            $name = data_get($datum, 'name');
            $comment = data_get($datum, 'comment');
            $ts1[] = sprintf(" * @property %s %s", $type, $name);
            $ts2[] = sprintf("         '%s' => ['title' => '%s', 'name' => '%s'],", $name, ucfirst($comment), ucfirst(implode(' ', explode('_', $name))));
            $ts3[] = sprintf('        "%s",', $name);
        }
        $f = sprintf(self::MODEL_FILE, $classname, implode("\n", $ts1), $classname, implode("\n", $ts2), $this->tableName, implode("\n", $ts3));
        $path = base_path('app/Models/' . $filename);
        file_put_contents($path, $f);
    }
}
