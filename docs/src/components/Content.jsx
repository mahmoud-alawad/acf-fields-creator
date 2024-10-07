import { useState } from 'react';

const methodDetails = {
  addText: {
    name: 'addText',
    description: 'Adds a text field with optional overrides.',
    parameters: [
      { name: 'name', type: 'string', description: 'Field name' },
      { name: 'label', type: 'string', description: 'Field label' },
      { name: 'overrides', type: 'array', description: 'Additional settings' },
    ],
    example: `
      const generator = new AcfGenerator();
      generator.addText('field_name', 'Field Label', { required: 1 });
    `,
  },
  addAccordion: {
    name: 'addAccordion',
    description: 'Adds an accordion field.',
    parameters: [
      { name: 'name', type: 'string', description: 'Field name' },
      { name: 'label', type: 'string', description: 'Field label' },
      { name: 'overrides', type: 'array', description: 'Additional settings' },
    ],
    example: `
      const generator = new AcfGenerator();
      generator.addAccordion('accordion_name', 'Accordion Label');
    `,
  },
  // Add more method details
};

export const Content =  function ({ activeMethod }) {
  if (!activeMethod) {
    return <p>Select a method from the sidebar to view details</p>;
  }

  const method = methodDetails[activeMethod];

  return (
    <div>
      <h2 className="text-2xl font-bold mb-4">{method.name}</h2>
      <p className="mb-4">{method.description}</p>

      <h3 className="text-xl font-bold mb-2">Parameters</h3>
      <ul className="mb-4">
        {method.parameters.map((param) => (
          <li key={param.name}>
            <strong>{param.name}</strong> ({param.type}): {param.description}
          </li>
        ))}
      </ul>

      <h3 className="text-xl font-bold mb-2">Example</h3>
      <pre className="bg-gray-100 p-4 rounded">{method.example}</pre>
    </div>
  );
}
