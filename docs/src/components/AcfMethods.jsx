import React from "react";

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

const AcfMethods = () => {
  return (
    <div>
      <h1>ACF Generator Methods</h1>
      <ul>
        {methods.map((method, index) => (
          <li key={index}>
            <strong>{method.name}</strong>: {method.description}
          </li>
        ))}
      </ul>
    </div>
  );
};

export default AcfMethods;
