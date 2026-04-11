<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class MCPController extends Controller
{
    public function sum(Request $request)
    {
        dd($request->all());
        $a = $request->input('a', 5);
        $b = $request->input('b', 7);

        $path = base_path('node-mcp');

        $process = new Process(['node', 'client.js', $a, $b]);

        $process->setWorkingDirectory($path);

        $process->setTimeout(10); // very important ⚡

        $process->run();

        if (!$process->isSuccessful()) {
            return response()->json([
                'status' => false,
                'error' => $process->getErrorOutput()
            ]);
        }

        $output = $process->getOutput();

        return response()->json([
            'status' => true,
            'data' => json_decode($output, true)
        ]);
    }
}