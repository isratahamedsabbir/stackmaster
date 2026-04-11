<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MCPController extends Controller
{
    public function sum(Request $request)
    {
        $a = (int) $request->input('a', 5);
        $b = (int) $request->input('b', 7);

        $path = base_path('node-mcp');
        $cmd = 'node client.js '.$a.' '.$b;

        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($cmd, $descriptorspec, $pipes, $path);

        if (! is_resource($process)) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to start process',
            ]);
        }

        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            return response()->json([
                'status' => false,
                'error' => 'Process failed',
                'exit_code' => $exitCode,
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => json_decode($output, true),
        ]);
    }
}
