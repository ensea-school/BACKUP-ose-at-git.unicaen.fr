-- public.unicaen_signature_signature definition

-- Drop table

-- DROP TABLE public.unicaen_signature_signature;

CREATE TABLE public.unicaen_signature_signature (
	id int4 NOT NULL,
	datecreated timestamp(0) DEFAULT NULL::timestamp without time zone NULL,
	"type" varchar(32) DEFAULT NULL::character varying NULL,
	status int4 DEFAULT 101 NOT NULL,
	"label" varchar(255) DEFAULT NULL::character varying NULL,
	description varchar(255) DEFAULT NULL::character varying NULL,
	datesend timestamp(0) DEFAULT NULL::timestamp without time zone NULL,
	dateupdate timestamp(0) DEFAULT NULL::timestamp without time zone NULL,
	document_path varchar(255) NOT NULL,
	document_remotekey varchar(255) DEFAULT NULL::character varying NULL,
	document_localkey varchar(255) DEFAULT NULL::character varying NULL,
	letterfile_key varchar(255) DEFAULT NULL::character varying NULL,
	letterfile_process varchar(255) DEFAULT NULL::character varying NULL,
	letterfile_url varchar(255) DEFAULT NULL::character varying NULL,
	allsigntocomplete bool DEFAULT false NOT NULL,
	"ordering" int4 DEFAULT 0 NOT NULL,
	notificationsrecipients bool DEFAULT false NOT NULL,
	CONSTRAINT unicaen_signature_signature_pkey PRIMARY KEY (id)
);


-- public.unicaen_signature_signatureflow definition

-- Drop table

-- DROP TABLE public.unicaen_signature_signatureflow;

CREATE TABLE public.unicaen_signature_signatureflow (
	id int4 NOT NULL,
	"label" varchar(255) DEFAULT NULL::character varying NULL,
	description varchar(255) DEFAULT NULL::character varying NULL,
	CONSTRAINT unicaen_signature_signatureflow_pkey PRIMARY KEY (id)
);


-- public.unicaen_signature_observer definition

-- Drop table

-- DROP TABLE public.unicaen_signature_observer;

CREATE TABLE public.unicaen_signature_observer (
	id int4 NOT NULL,
	signature_id int4 NULL,
	firstname varchar(64) DEFAULT NULL::character varying NULL,
	lastname varchar(64) DEFAULT NULL::character varying NULL,
	email varchar(256) NOT NULL,
	CONSTRAINT unicaen_signature_observer_pkey PRIMARY KEY (id),
	CONSTRAINT fk_eac19423ed61183a FOREIGN KEY (signature_id) REFERENCES public.unicaen_signature_signature(id)
);
CREATE INDEX idx_eac19423ed61183a ON public.unicaen_signature_observer USING btree (signature_id);


-- public.unicaen_signature_process definition

-- Drop table

-- DROP TABLE public.unicaen_signature_process;

CREATE TABLE public.unicaen_signature_process (
	id int4 NOT NULL,
	datecreated timestamp(0) DEFAULT NULL::timestamp without time zone NULL,
	lastupdate timestamp(0) DEFAULT NULL::timestamp without time zone NULL,
	status int4 NULL,
	currentstep int4 NOT NULL,
	document_name varchar(255) NOT NULL,
	signatureflow_id int4 NULL,
	CONSTRAINT unicaen_signature_process_pkey PRIMARY KEY (id),
	CONSTRAINT fk_994855d2b4090c8a FOREIGN KEY (signatureflow_id) REFERENCES public.unicaen_signature_signatureflow(id)
);
CREATE INDEX idx_994855d2b4090c8a ON public.unicaen_signature_process USING btree (signatureflow_id);


-- public.unicaen_signature_recipient definition

-- Drop table

-- DROP TABLE public.unicaen_signature_recipient;

CREATE TABLE public.unicaen_signature_recipient (
	id int4 NOT NULL,
	signature_id int4 NULL,
	status int4 DEFAULT 101 NOT NULL,
	firstname varchar(64) DEFAULT NULL::character varying NULL,
	lastname varchar(64) DEFAULT NULL::character varying NULL,
	email varchar(256) NOT NULL,
	phone varchar(20) DEFAULT NULL::character varying NULL,
	dateupdate timestamp(0) DEFAULT NULL::timestamp without time zone NULL,
	datefinished timestamp(0) DEFAULT NULL::timestamp without time zone NULL,
	keyaccess varchar(255) DEFAULT NULL::character varying NULL,
	"comment" varchar(255) DEFAULT NULL::character varying NULL,
	CONSTRAINT unicaen_signature_recipient_pkey PRIMARY KEY (id),
	CONSTRAINT fk_f47c5330ed61183a FOREIGN KEY (signature_id) REFERENCES public.unicaen_signature_signature(id)
);
CREATE INDEX idx_f47c5330ed61183a ON public.unicaen_signature_recipient USING btree (signature_id);


-- public.unicaen_signature_signatureflowstep definition

-- Drop table

-- DROP TABLE public.unicaen_signature_signatureflowstep;

CREATE TABLE public.unicaen_signature_signatureflowstep (
	id int4 NOT NULL,
	recipientsmethod varchar(64) DEFAULT NULL::character varying NULL,
	"label" varchar(64) DEFAULT NULL::character varying NULL,
	letterfilename varchar(256) NOT NULL,
	"level" varchar(256) NOT NULL,
	"ordering" int4 NOT NULL,
	allrecipientssign bool DEFAULT true NOT NULL,
	notificationsrecipients bool DEFAULT false NOT NULL,
	"options" text NULL,
	signatureflow_id int4 NULL,
	editablerecipients bool DEFAULT false NOT NULL,
	CONSTRAINT unicaen_signature_signatureflowstep_pkey PRIMARY KEY (id),
	CONSTRAINT fk_a575dc3eb4090c8a FOREIGN KEY (signatureflow_id) REFERENCES public.unicaen_signature_signatureflow(id)
);
CREATE INDEX idx_a575dc3eb4090c8a ON public.unicaen_signature_signatureflowstep USING btree (signatureflow_id);


-- public.unicaen_signature_process_step definition

-- Drop table

-- DROP TABLE public.unicaen_signature_process_step;

CREATE TABLE public.unicaen_signature_process_step (
	id int4 NOT NULL,
	process_id int4 NULL,
	signature_id int4 NULL,
	signatureflowstep_id int4 NULL,
	CONSTRAINT unicaen_signature_process_step_pkey PRIMARY KEY (id),
	CONSTRAINT fk_cf70b0a57ec2f574 FOREIGN KEY (process_id) REFERENCES public.unicaen_signature_process(id),
	CONSTRAINT fk_cf70b0a5c352c4 FOREIGN KEY (signatureflowstep_id) REFERENCES public.unicaen_signature_signatureflowstep(id),
	CONSTRAINT fk_cf70b0a5ed61183a FOREIGN KEY (signature_id) REFERENCES public.unicaen_signature_signature(id)
);
CREATE INDEX idx_cf70b0a57ec2f574 ON public.unicaen_signature_process_step USING btree (process_id);
CREATE INDEX idx_cf70b0a5c352c4 ON public.unicaen_signature_process_step USING btree (signatureflowstep_id);
CREATE UNIQUE INDEX uniq_cf70b0a5ed61183a ON public.unicaen_signature_process_step USING btree (signature_id);