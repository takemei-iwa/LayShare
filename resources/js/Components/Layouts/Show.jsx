import React from "react";
import { router } from '@inertiajs/react';

export default function Show({ layouts }) {
    return (
        <div class="grid grid-cols-3 gap-2">
            {layouts.map((layout) => (
                <div key={layout.id}>
                    <div class="w-full aspect-w-9 aspect-h-5 overflow-hidden bg-white">
                        <img class="object-cover w-full h-auto object-left-top" alt=""
                            src={layout.thumbnail}
                            onClick={() => router.get(`/layouts/${layout.id}`)} />
                    </div>
                </div>
            ))}
        </div>
    );
}