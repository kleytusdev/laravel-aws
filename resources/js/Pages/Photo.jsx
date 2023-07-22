import React, { useState } from "react";
import axios from "axios";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";

export default function Photo({ auth }) {
  const [selectedFile, setSelectedFile] = useState(null);

  const handleFileChange = (event) => {
    setSelectedFile(event.target.files[0]);
  };

  const handleUpload = () => {
    const formData = new FormData();
    formData.append("photo", selectedFile);

    axios
      .post(route("photo.store"), formData)
      .then((response) => {
        // Aquí puedes manejar la respuesta del backend si es necesario
      })
      .catch((error) => {
        // Aquí puedes manejar errores de la petición si ocurren
      });
  };

  return (
    <AuthenticatedLayout
      user={auth.user}
      header={
        <h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          Photos
        </h2>
      }
    >
      <Head title="Dashboard" />
      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 text-gray-900 dark:text-gray-100">
              Sube una imagen
            </div>
            <div className="p-6 text-gray-900 dark:text-gray-100">
              <input type="file" onChange={handleFileChange} />
            </div>
            <button onClick={handleUpload}>Subir</button>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
