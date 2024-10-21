import { CodeBlock, dracula } from "react-code-blocks";

type ComponentProps = {
  className?: string;
  title?: string;
  subtitle?: string;
  children?: React.ReactNode;
};

const GetStarted = (props: ComponentProps) => {
  const installationCode = `composer require mahmoud-alawad/acf-fields-generator`;
  const usageCode = `<?php
  use AcfCreator\\Create;
    $myFields = (new AcfCreate())->addTab('generalTab', __('General', 'domain'))
        ->addText('address')
        ->addText('agency')->setWidth('50%')
        ->addEmail('email')->setWidth('50%')
        ->addText('coords', 'Lat & Long', [
            'instructions' => 'use (-) between lat and long ex: 45.5850728-12.0458242',
        ])
        ->addTab('optionsTab', __('Options', 'domain'))
        ->addGroup('options')
        ->addTextarea('marker', 'svg marker')
        ->addText('class', 'Class', [
            'instructions' => 'add class name without (.)',
        ])
        ->endGroup(); `;
  const outPutSample = `<?php 
  Array (
    [0] = 
        (
            [instructions] = ' '
            [required] = 0
            [wrapper] = 
                (
                    [width] =  ' ' 
                    [class] = ' ' 
                    [id] =  ' '
                )

            [label] = General
            [name] = generalTab
            [type] = tab
            [placement] = top
            [endpoint] = 0
        )

    [1] = 
        (
            [instructions] = ' '
            [required] = 0
            [wrapper] = 
                (
                    [width] =  ' ' 
                    [class] = ' ' 
                    [id] =  ' '
                )

            [label] = Address
            [name] = address
            [type] = text
            [default_value] = ' ' 
            [placeholder] = ' ' 
            [prepend] = 
            [append] = 
            [maxlength] = 
        )

    [2] = 
        (
            [instructions] = ' '
            [required] = 0
            [wrapper] = 
                (
                    [class] = 
                    [id] = 
                    [width] = 50%
                )

            [label] = Agency
            [name] = agency
            [type] = text
            [default_value] = ' ' 
            [placeholder] = ' ' 
            [prepend] = 
            [append] = 
            [maxlength] = 
        )

    [3] = 
        (
            [instructions] = ' '
            [required] = 0
            [wrapper] = 
                (
                    [class] = 
                    [id] = 
                    [width] = 50%
                )

            [label] = Email
            [name] = email
            [type] = email
            [default_value] = ' ' 
            [placeholder] = ' ' 
            [prepend] = 
            [append] = 
        )

    [4] = 
        (
            [instructions] = ' 'use (-) between lat and long ex: 45.5850728-12.0458242
            [required] = 0
            [wrapper] = 
                (
                    [width] =  ' ' 
                    [class] = ' ' 
                    [id] =  ' '
                )

            [label] = Lat &amp; Long
            [name] = coords
            [type] = text
            [default_value] = ' ' 
            [placeholder] = ' ' 
            [prepend] = 
            [append] = 
            [maxlength] = 
        )

    [5] = 
        (
            [instructions] = ' '
            [required] = 0
            [wrapper] = 
                (
                    [width] =  ' ' 
                    [class] = ' ' 
                    [id] =  ' '
                )

            [label] = Options
            [name] = optionsTab
            [type] = tab
            [placement] = top
            [endpoint] = 0
        )

    [6] = 
        (
            [instructions] = ' '
            [required] = 0
            [wrapper] = 
                (
                    [width] =  ' ' 
                    [class] = ' ' 
                    [id] =  ' '
                )

            [label] = Options
            [name] = options
            [type] = group
            [layout] = block
            [sub_fields] = 
                (
                    [0] = 
                        (
                            [instructions] = ' '
                            [required] = 0
                            [wrapper] = 
                                (
                                    [width] = 
                                    [class] = 
                                    [id] = 
                                )

                            [label] = svg marker
                            [name] = marker
                            [type] = textarea
                            [default_value] = ' ' 
                            [placeholder] = ' ' 
                            [maxlength] = 
                            [rows] = 3
                            [new_lines] = br
                        )

                    [1] = 
                        (
                            [instructions] = 'add class name without (.)'
                            [required] = 0
                            [wrapper] = 
                                (
                                    [width] = 
                                    [class] = 
                                    [id] = 
                                )

                            [label] = Class
                            [name] = class
                            [type] = text
                            [default_value] = ' ' 
                            [placeholder] = ' ' 
                            [prepend] = 
                            [append] = 
                            [maxlength] = 
                        )

                )

        )

)
`;

  return (
    <div {...props} className="flex-1 py-2 md:pl-3">
      <h3 className="h3 mt-6">Getting Started With Acf Fields Creator</h3>
      <div className="h4 mt-4">Installation</div>
      <section id="installation" className="mt-8">
        <CodeBlock
          customStyle={{ marginTop: "20px" }}
          theme={dracula}
          text={installationCode}
          language="php"
          showLineNumbers={false}
        />
      </section>
      <section id="usage" className="mt-6">
        <h3 className="h3">usage</h3>
        <CodeBlock
          customStyle={{ marginTop: "20px" }}
          theme={dracula}
          text={usageCode}
          language="php"
          showLineNumbers={false}
        />

        <div className="h4 mt-4">this will output</div>
        <CodeBlock
          customStyle={{ marginTop: "20px" }}
          theme={dracula}
          text={outPutSample}
          wrapLongLines={true}
          language="php"
          showLineNumbers={false}
        />
      </section>
    </div>
  );
};

export { GetStarted };
