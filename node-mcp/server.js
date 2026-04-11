import { Server } from "@modelcontextprotocol/sdk/server/index.js";
import { StdioServerTransport } from "@modelcontextprotocol/sdk/server/stdio.js";

import {
    ListToolsRequestSchema,
    CallToolRequestSchema,
} from "@modelcontextprotocol/sdk/types.js";

const server = new Server(
    {
        name: "laravel-mcp-server",
        version: "1.0.0",
    },
    {
        capabilities: {
            tools: {},
        },
    },
);

// tools list
server.setRequestHandler(ListToolsRequestSchema, async () => {
    return {
        tools: [
            {
                name: "sum",
                description: "Add two numbers",
                inputSchema: {
                    type: "object",
                    properties: {
                        a: { type: "number" },
                        b: { type: "number" },
                    },
                    required: ["a", "b"],
                },
            },
        ],
    };
});

// tool call
server.setRequestHandler(CallToolRequestSchema, async (request) => {
    if (request.params.name === "sum") {
        const { a, b } = request.params.arguments;

        return {
            content: [
                {
                    type: "text",
                    text: `Sum is ${a + b}`,
                },
            ],
        };
    }

    throw new Error("Tool not found");
});

const transport = new StdioServerTransport();
await server.connect(transport);
