<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\Admin;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminRoleListBuilder extends ListBuilder
{
    public static string $name = 'Admin Role';

    public static string $permissionPrefix = 'Admin Role';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = Admin::orderBy('created_at','desc')->get();

        return self::buildQuery(
            $query,
            $request
        );
    }



    public static function columns(): array
    {

        $data = [
            new ListBuilderColumn(
                name: 'Name',
                property: 'name',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
            new ListBuilderColumn(
                name: 'Email',
                property: 'email',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),

        ];
        return $data;
    }
}
