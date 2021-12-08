class VW_Connect {

    static StatusCodes = {
        OK : 200,
        CREATED : 201,
        NOTFOUND : 404,
        SERVERERR : 500
    } 

    constructor(options = {}) {
        const def_options = {
            debug : false
        }

        this.options = {...def_options,...options};
    }

    /**
     * Asynchronous POST Request
     * @param {String} url Url to Call From
     * @param {Object} paramArray Javascript key:value Object for the Data Sent
     * @param {function(status,response)} callback Data Callback, status shows STATUS_OK,STATUS_NOK or STATUS_NOT_JSON
     */
    POST(url,paramArray,callback = "") {
        let _this = this;
            let xhr = new XMLHttpRequest();

        // Event Listener upon Return Value Received
        xhr.addEventListener("loadend",function(e){
            if(e.target.status == VW_Connect.StatusCodes.OK) {
                let [type, jsonData] = _this.safeJsonParse(e.target.responseText);        
                if(type) {
                    // Standard Ajax calls should show a "status" = "OK"
                    if(jsonData["status"] == "OK") {
                        if(checkType(callback) === 'function') {
                            callback("STATUS_OK",jsonData["data"]);
                        }
                    } else {
                        if(checkType(callback) === 'function') {                            
                            callback("STATUS_NOK",jsonData["data"]);
                        }
                    }  
                } else {
                    if(checkType(callback) === 'function') {                 
                        callback("STATUS_NOT_JSON",jsonData);
                    }
                }           
            } else {
                // Return Message Check
                switch(e.target.status) {
                    case VW_Connect.StatusCodes.NOTFOUND:
                        console.log("POST-response","The URL was not found");
                        break;
                    default:
                        console.log("POST-response","An Error Occured!");
                        break;

                }
            }
        });

        // Create the POST 
        xhr.open("POST",url,true);
        // Add Headers
        xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded")
        

        // Prepare Parameter Array
        let paramStr = _this.arrayToParamString(paramArray);

        // Send request
        xhr.send(paramStr);
    }

    /**
     * Asynchronous Get Request
     * @param {String} url Url to Call From
     * @param {Object} paramArray Javascript key:value Object for the Data Sent
     * @param {function(status,response)} callback Data Callback, status shows STATUS_OK,STATUS_NOK or STATUS_NOT_JSON
     */
    GET(url,paramArray, callback = "") {
        let _this = this;
        let xhr = new XMLHttpRequest();

        // Event Listener upon Return Value Received
        xhr.addEventListener("loadend",function(e){
            if(e.target.status == VW_Connect.StatusCodes.OK) {
                let [type, jsonData] = _this.safeJsonParse(e.target.responseText);        
                if(type) {
                    // Standard Ajax calls should show a "status" = "OK"
                    if(jsonData["status"] == "OK") {
                        if(checkType(callback) === 'function') {
                            callback("STATUS_OK",jsonData["data"]);
                        }
                    } else {
                        if(checkType(callback) === 'function') {                            
                            callback("STATUS_NOK",jsonData["data"]);
                        }
                    }  
                } else {
                    if(checkType(callback) === 'function') {                 
                        callback("STATUS_NOT_JSON",jsonData);
                    }
                }         
            } else {
                // Return Message Check
                switch(e.target.status) {
                    case VW_Connect.StatusCodes.NOTFOUND:
                        console.log("POST-response","The URL was not found");
                        break;
                    default:
                        console.log("POST-response","An Error Occured!");
                        break;

                }
            }
        });

        // Create the GET Request
        xhr.open("GET",url + "?" + _this.arrayToParamString(paramArray),true);
        // Add Headers
        xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded")
        

        // Send request
        xhr.send();
    }

    /**
     * Send Form 
     * @param {String} formF Form or Selector for Form
     * @param {String} formAction Action String for the Form. Optionally may take this from form Directly
     * @param {String} formMethod Method String for the Form. Optionally may take this from form Directly
     * @param {function(status,response)} callback Data Callback, status shows STATUS_OK,STATUS_NOK or STATUS_NOT_JSON
     */
    SendForm(form, formAction = "", formMethod = "", callback = "" ) 
    {
        let _this = this;
            // Select Form
            switch(checkType(form)) {
                case "string":                
                    formF = document.querySelector(form);
                    break;
                case "element":
                    break;
                default:
                    callback(false,"NOT_FORM");
                    return;
            }
        // Check if it is indeed a form
        if (!form || form.nodeName.toLowerCase() !== "form") {
            console.log("[SendForm] - Not a form!");
            return;
        }        
        let field = {};

        let formData = new FormData(form);
        if(_this.options.debug) {console.log("sendForm-form data",formData)}
        
        if(_this.options.debug) {
            console.log("--------formData - elements -------------------");
            for (const formElement of formData) {
                console.log(formElement);
            }
            console.log("--------formData - elements -------------------");
        }


        for (var [key, value] of formData.entries()) { 

            if(field[key] === undefined) {
                if(key.indexOf("[]")>0) {
                    field[key] = [];
                    field[key].push(value);
                } else {
                    field[key] = value;
                }
            } else {
                if(key.indexOf("[]")>0) {
                    field[key].push(value);
                } else {
                    field[key] = value;
                }
            }
        }

        
        if(_this.options.debug) {console.log("sendForm-form field",field)};

        field = { 
            submit : "form",
            a : (field.a === undefined ? '' : field.a), 
            data : JSON.stringify(field)
        }; 

        let action = formAction.length > 0 ? formAction : form.action;
        let method = formMethod.length> 0 ?  formMethod : form.method;

        switch(String(method).toLowerCase()) {
            case "post":
                if(checkType(callback) === 'function') {
                    _this.POST(action,field,function(status,response){callback(status,response);}) 
                } else {
                    _this.POST(action,field);
                }
                break;
            case "get":
                if(checkType(callback) === 'function') {
                    _this.GET(action,field,function(status,response){callback(status,response);}) 
                } else {
                    _this.GET(action,field);
                }
                break;
        }   
    }

    /**
     * Creates a String Paramater Array to be sent to url
     * @param {Object} paramArray Javascript Object to Stringify
     * @returns 
     */
    arrayToParamString(paramArray,urlFriendly = true) {
        let paramStr = "";
        for(let key in paramArray) {
            console.log(key,paramArray[key]);
            paramStr += `${typeof key === "string" ? key.trim() : key}=${typeof paramArray[key] === "string" ? paramArray[key].trim() : paramArray[key]}&`;
        }   
        return (urlFriendly? encodeURI(paramStr.slice(0,-1)) : paramStr.slice(0,-1));
    } 

    /**
     * Safe JSON Parser
     * @param {String/JSONString} str A String or JSON String 
     * @returns if JSON is detected, returns a json object, if not, returns original string
     */
    safeJsonParse(str) {
        let err,json;
        try {
            [err , json] =  [null, JSON.parse(str)];   
        } catch (error) {
            err = error;   
        }
        if(err) {
            return [false,str];
        } else {
            return [true,json];
        }
    }    
}

const TypesOf = Object.freeze({
    elem : {name: "element"},
    string : {name: "string"},
    bool : {name : "boolean"},
    func : {name:"function"},
    num : {name:"number"},
    sym : {name:"symbol"},
    undef : {name:"undefined"},
    obj : {name:"object"},
    arr : {name:"array"}
});

/**
 * Check Type
 * Returns Type of variable 
 * @param {*} element Variable to Check
 * @returns TypeOf Variable, element,string,boolean,function,number,symbol,undefined,object,array
 */
function checkType(element) {
    let isElemt =  element instanceof Element;
    /* let isHTML = element instanceof HTMLDocument; */
    let whatType = typeof element;
    if(isElemt) {
        return TypesOf.elem.name;
    }
    else {
        for(let key in TypesOf) {
            if(TypesOf[key].name == whatType) {
                if(TypesOf[key].name == "object") {
                    if(Array.isArray(element)) {
                        return TypesOf.arr.name;
                    } else {
                        return TypesOf.obj.name;
                    }
                }
                return TypesOf[key].name;
            }
        }
    }
}

class Listeners {
    constructor(options={}) {
        const def_options = {
            debug : false,
        };

        this.currOpt = {...def_options,...options};
    }

    form(element,optListener = {name:"",listenType:""},optAction="",optMethod="",callback="") {
        let _this = this;
        if(_this.currOpt.debug) {
            console.log("form_elem",element + "(" + checkType(element) + ")");
            console.log("optional Listener",optListener.name + "(" + checkType(optListener.name) + ") - " + optListener.listenType);
            console.log("optAction",optAction);
            console.log("optMethod",optMethod);
        }

        // optional element check
        let isOpt = false;

        switch(checkType(element)) {
            case "string":                
                element = document.querySelector(element);
                break;
            case "element":
                break;
            default:
                callback(false,"NOT_FORM");
                return;
        }
        if(element == undefined) {callback(false,"NOT_FORM");return;} 

        // If there is an alternative listener as well, a button outside the form for example
        if(optListener.name != undefined) {
            switch(checkType(optListener.name)) {
                case "string":                
                    optListener.name = document.querySelector(optListener.name);
                    isOpt = true;
                    break;
                case "element":
                    isOpt = true;
                    break;
                default:
                    callback(false,"NOT_VALID_LISTENER");
                    return;
            }
        }   
        
        // Add Optional Listener for the button element
        if(isOpt) {
            optListener.name.addEventListener(optListener.listenType,function(e){sendForm(e)});
        }             
        // Add Submit Listener for the element
        element.addEventListener("submit",function(e){sendForm(e)}); 

        function sendForm(e) {
            e.preventDefault();
            e.stopPropagation();
            let Form = new VW_Connect();
            Form.SendForm(element,optAction,optMethod,function(a,b){callback(a,b);})
        }
    }
}

class FormValidation {
    constructor(form) {
        let _this = this;
        // Rules 
        _this.rules = {};
        switch(checkType(form)) {
            case "string":                
                _this.form = document.querySelector(form);
                break;
            case "element":
                _this.form  = form;
                break;
            default:
                callback(false,"NOT_FORM");
                return;
        }
    }

    addRule(name, ruleType, ruleParam="",ruleText="") {
        let _this = this;
        _this.rules[name] = {
            type : ruleType,
            param : ruleParam,
            caption : ruleText
        };
    }

    validate(callback = "") {
        let _this = this;
        let _isValid = true;
        for(key in _this.rules) {
            let data = _this.form.elements[_this.rules[key].name];
            if(data) {
                switch(String(_this.rules[key].param).toLowerCase()) {
                    // length
                    case "length":
                        if(data.value.length > _this.rules[key].param) {
                            var caption = (_this.rules[key].caption.length < 1 ?
                                "Answer is too Long" :  _this.rules[key].caption.length)
                            data.setCustomValidation(caption);
                            if(_isValid) {_isValid = false;}                            
                        } else {
                            data.setCustomValidation("");
                        }
                        break;
                    // required
                    case "required":
                        if(data.value == "") {
                            var caption = (_this.rules[key].caption.length < 1 ?
                                "A Response is Required!" :  _this.rules[key].caption.length)
                            data.setCustomValidation(caption);
                            if(_isValid) {_isValid = false;}     
                        } else {
                            data.setCustomValidation("");
                        }
                        break;
                    // number
                    case "number":
                        if(isNaN(data.value)) {
                            var caption = (_this.rules[key].caption.length < 1 ?
                                "Response must be a number!" :  _this.rules[key].caption.length)
                            data.setCustomValidation(caption);
                            if(_isValid) {_isValid = false;}     
                        } else {
                            data.setCustomValidation("");
                        }
                        break;
                    // max
                    case "max":
                        if(Number(data.value) > _this.rules[key].param) {
                            var caption = (_this.rules[key].caption.length < 1 ?
                                "Number Too Large!" :  _this.rules[key].caption.length)
                            data.setCustomValidation(caption);
                            if(_isValid) {_isValid = false;}     
                        } else {
                            data.setCustomValidation("");
                        }
                        break;
                    // min
                    case "min":
                        if(Number(data.value) < _this.rules[key].param) {
                            var caption = (_this.rules[key].caption.length < 1 ?
                                "Number Too Small!" :  _this.rules[key].caption.length)
                            data.setCustomValidation(caption);
                            if(_isValid) {_isValid = false;}     
                        } else {
                            data.setCustomValidation("");
                        }
                        break;
                }
            }
        }

        if(_isValid) {
            if(checkType(callback) == "function") {
                callback(true);
            } else {
                return true;
            }
        } else {
            if(checkType(callback) == "function") {
                callback(false);
            } else {
                return false;
            }
        }
    }
}
