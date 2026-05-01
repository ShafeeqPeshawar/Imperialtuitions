"use client";

import dynamic from "next/dynamic";
import { Quill } from "react-quill-new";
import "react-quill-new/dist/quill.snow.css";

const ReactQuill = dynamic(() => import("react-quill-new"), { ssr: false });

const FONT_WHITELIST = [
  "arial",
  "times-new-roman",
  "georgia",
  "verdana",
  "tahoma",
  "trebuchet-ms",
  "courier-new",
  "helvetica",
];

type QuillFontModule = { whitelist: string[] };
const Font = Quill.import("formats/font") as QuillFontModule;
Font.whitelist = FONT_WHITELIST;
(Quill as unknown as { register: (format: unknown, overwrite?: boolean) => void }).register(Font, true);

const modules = {
  toolbar: [
    [{ header: [1, 2, 3, false] }],
    [{ font: FONT_WHITELIST }, { size: [] }],
    ["bold", "italic", "underline", "strike"],
    [{ color: [] }, { background: [] }],
    [{ list: "ordered" }, { list: "bullet" }],
    [{ indent: "-1" }, { indent: "+1" }],
    [{ align: [] }],
    ["link"],
    ["clean"],
  ],
};

const formats = [
  "header",
  "font",
  "size",
  "bold",
  "italic",
  "underline",
  "strike",
  "color",
  "background",
  "list",
  "bullet",
  "indent",
  "align",
  "link",
];

export function RichTextEditor({
  value,
  onChange,
  placeholder,
}: {
  value: string;
  onChange: (value: string) => void;
  placeholder?: string;
}) {
  return (
    <div className="rich-editor-wrap">
      <ReactQuill theme="snow" value={value} onChange={onChange} placeholder={placeholder} modules={modules} formats={formats} />
    </div>
  );
}
