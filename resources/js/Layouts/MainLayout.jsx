import React from "react";
import Authenticated from "@/Layouts/AuthenticatedLayout";
import GuestLayout from "@/Layouts/GuestLayout";

export default function MainLayout( { user, children }) {
  console.log(user);
  const isLoggedIn = user !== null;
  if (isLoggedIn) {
      return(
          <Authenticated user={user}>
              {children}
          </Authenticated>
      );
  } else{
      return(
          <GuestLayout>
              {children}
          </GuestLayout>
      );
  }
}