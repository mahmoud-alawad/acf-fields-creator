import { useState } from 'react';

const methods = [
    { name: "addText", description: "Add a text field to the form." },
    { name: "addAccordion", description: "Add an accordion field." },
    { name: "addOembed", description: "Add an Oembed field." },
    { name: "addTaxonomy", description: "Add a taxonomy selection field." },
    { name: "addRelationShip", description: "Add a relationship field." },
    { name: "addPostObject", description: "Add a post object field." },
    { name: "addPageLink", description: "Add a page link field." },
    { name: "addNumber", description: "Add a number input field." },
    { name: "addDate", description: "Add a date picker field." },
    { name: "addTime", description: "Add a time picker field." },
    { name: "addImage", description: "Add an image upload field." },
    { name: "addFile", description: "Add a file upload field." },
    { name: "addSelect", description: "Add a dropdown select field." },
    { name: "addEmail", description: "Add an email input field." },
    { name: "addTrueFalse", description: "Add a true/false toggle." },
    { name: "addRepeater", description: "Add a repeater field." },
    { name: "addGroup", description: "Add a group of fields." },
    { name: "addLink", description: "Add a link input field." },
    { name: "addGallery", description: "Add a gallery of images." },
    { name: "addTextarea", description: "Add a text area field." },
    { name: "addWysiwg", description: "Add a WYSIWYG editor field." },
    { name: "addTab", description: "Add a tab navigation element." },
    { name: "addMessage", description: "Add a message display field." },
    { name: "addColor", description: "Add a color picker field." },
];

export const Sidebar =  function () {
  const [activeMethod, setActiveMethod] = useState(null);

  return (
    <div className="w-64 h-full bg-gray-800 text-white flex flex-col">
      <h1 className="text-xl p-4">ACF Generator</h1>
      <ul className="flex-grow overflow-y-auto">
        {methods.map((method) => (
          <li key={method.name} className="hover:bg-gray-700">
            <button
              className="w-full text-left p-4"
              onClick={() => setActiveMethod(method.name)}
            >
              {method.name}
            </button>
          </li>
        ))}
      </ul>
    </div>
  );
}
