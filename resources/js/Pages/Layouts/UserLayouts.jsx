import React, { useState } from "react";
import { Link } from '@inertiajs/react';
import { router } from '@inertiajs/react';
import MainLayout from "@/Layouts/MainLayout";
import Show from "@/Components/Layouts/Show";

export default function UserLayouts(props) {
    const { layouts } = props;
    console.log(layouts);
    console.log("Index props : ",props.auth.user);

    return (
        <MainLayout user={props.auth.user}>
            <Show layouts={layouts} />
        </MainLayout>
    );
}