;
var editormdView = editormd;
editormdView.katexURL= {
    js  : "/public/vendor/katex/dist/katex.min",
    css : "/public/vendor/katex/dist/katex.min"
};
function markdownToHTMLView(to, form) {
    //$.get("/public/vendor/editormd/examples/test.md", function(markdown) {
    var txt = $(form).text();
    $(form).text('');
    editormdView.markdownToHTML(to, {
        width: "100%",
        markdown        : txt,
        htmlDecode      : false,
        htmlDecode      : "style,script,iframe|on*",  // you can filter tags decode
        tex              : true,
        tocm             : true,
        emoji            : false,
        taskList         : false,
        codeFold         : true,
        searchReplace    : true,
        flowChart        : true,
        sequenceDiagram  : true,
        autoLoadKaTeX    : false,
        path:"/public/vendor/editormd/lib/",
    });
}