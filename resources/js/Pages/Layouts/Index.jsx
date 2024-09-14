import React from "react";
import { Link } from '@inertiajs/react';
import Authenticated from "@/Layouts/AuthenticatedLayout";

export default function Index(props) {
    return (
        <Authenticated user={props.auth.user} header={
            <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                Layouts
            </h2>
        }>
            <h1> layouts</h1>   
            <Link href="/layouts/create">レイアウトの投稿</Link>
        </Authenticated>
    );
}