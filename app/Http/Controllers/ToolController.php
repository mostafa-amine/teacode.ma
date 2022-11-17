<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function sqldump(Request $request)
    {
        $dumpPath = database_path('dumps/dump.sql');
        \Spatie\DbDumper\Databases\MySql::create()
            ->setDbName('teacode.ma')
            ->setUserName('root')
            ->setPassword('')
            ->dumpToFile($dumpPath);
            
            return \Response::download($dumpPath, "dump.sql", [
                'Content-Type' => 'application/sql',
                // 'Content-Transfer-Encoding'=> 'binary',
                // 'Accept-Ranges'=> 'bytes'
            ]);
    }
}
