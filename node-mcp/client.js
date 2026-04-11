import { Client } from "@modelcontextprotocol/sdk/client/index.js";
import { StdioClientTransport } from "@modelcontextprotocol/sdk/client/stdio.js";

async function run(a, b) {
    const transport = new StdioClientTransport({
        command: "node",
        args: ["server.js"],
    });

    const client = new Client({
        name: "laravel-client",
        version: "1.0.0",
    });

    await client.connect(transport);

    const result = await client.callTool({
        name: "sum",
        arguments: {
            a: Number(a),
            b: Number(b),
        },
    });

    console.log(JSON.stringify(result));
}

const args = process.argv.slice(2);
run(args[0], args[1]);
