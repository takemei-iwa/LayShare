import React from "react";
import { Link } from '@inertiajs/react';
export default function Index() {
    return (
        <div>
            <h1> layouts</h1>   
            <Link href="/layouts/create">レイアウトの投稿</Link>
        </div>
    );
}