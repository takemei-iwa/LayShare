import React, { useState } from "react";
import { Link } from '@inertiajs/react';
import Authenticated from "@/Layouts/AuthenticatedLayout";
import { router } from '@inertiajs/react'

export default function Index(props) {
    const { layouts } = props;
    console.log(layouts);
    console.log(props);
    const { d, setD } = useState(layouts[0].thumbnail);

    return (
        <Authenticated user={props.auth.user} header={
            <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                Layouts
            </h2>
        }>
            <h1> layouts</h1>
            <p>{layouts[0].id}</p> 
            { layouts.map((layout) => (     
                    <div key={layout.id}>
                        <img 
                            src = {layout.thumbnail} 
                            onClick = {() => router.get(`/layouts/${layout.id}`) }/>
                    </div>
                )) }
            <Link href="/layouts/create">レイアウトの投稿</Link>
        </Authenticated>
    );
}